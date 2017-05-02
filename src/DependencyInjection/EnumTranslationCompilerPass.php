<?php
namespace Hostnet\Bundle\EntityTranslationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
class EnumTranslationCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $resource_files = null;

        foreach ($container->getDefinition('translator.default')->getArguments() as $argument) {
            if (!is_array($argument) || !isset($argument['resource_files'])) {
                continue;
            }

            $resource_files = (array) $argument['resource_files'];
        }

        if (null === $resource_files) {
            throw new \LogicException('Expected a translator argument to have an array with "resource_files".');
        }

        foreach ($resource_files as $files) {
            $this->registerEnums($container, array_filter($files, function ($file) {
                return preg_match('~/enum\.[a-z]+\.[a-z]+$~', $file) === 1;
            }));
        }
    }

    /**
     * Register the enums found in the yml file with the translation component.
     *
     * For instance, the file enum.en.yml with the content:
     *     hostnet:
     *         order:
     *             entity:
     *                 order_status:
     *                     accepted : "Accepted"
     *
     * Will result in the class Hostnet\Order\Entity\OrderStatus to be
     * registred as an enum with english translations. We assume that the last
     * level in the array are the const names and everything before that is
     * the class name.
     *
     * @param ContainerBuilder $container
     * @param array $enum_files
     */
    protected function registerEnums(ContainerBuilder $container, array $enum_files)
    {
        $translator = $container->getDefinition('translator.default');

        foreach ($enum_files as $file) {
            list(, $locale, $format) = explode('.', basename($file), 3);

            if (strtolower($format) !== 'yml') {
                throw new \RuntimeException(sprintf("Unsupported enum translation file format '%s'.", $format));
            }

            $translations = Yaml::parse(file_get_contents($file));

            if (!is_array($translations)) {
                throw new \RuntimeException(sprintf(
                    "The translations yml should contain an array, got '%s'.",
                    gettype($translations)
                ));
            }

            // convert the flatten keys into a list of enum classes.
            $enums = array_unique(array_map(function ($str) {
                $parts = explode(".", $str);
                array_pop($parts);

                return preg_replace_callback(
                    '/_(.?)/',
                    function ($matches) {
                        return strtoupper($matches[1]);
                    },
                    implode("\\", array_map('ucfirst', $parts))
                );
            }, array_keys($this->flattenKeys($translations))));

            // register the found enums into the correct domain and locale
            foreach ($enums as $enum) {
                $translator->addMethodCall('addResource', ['enum', $file, $locale, $enum]);
            }
        }
    }

    /**
     * Flatten the YML array so we get one key per value.
     *
     * For instance:
     *    ["foo" => ["bar" => "test"]]
     * Will become:
     *    ["foo.bar" => "test"]
     *
     * @param array $array
     * @return array
     */
    private function flattenKeys(array $array)
    {
        $b = [];
        foreach ($array as $key => $value) {
            if (!is_array($value)) {
                return $array;
            }
            $a = $this->flattenKeys($value);

            foreach ($a as $sub_key => $sub_value) {
                $b[$key . '.' . $sub_key] = $sub_value;
            }
        }
        return $b;
    }
}
