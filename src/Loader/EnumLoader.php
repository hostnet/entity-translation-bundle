<?php
namespace Hostnet\Bundle\EntityTranslationBundle\Loader;

use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\MessageCatalogue;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
class EnumLoader implements LoaderInterface
{
    /**
     * @var YamlFileLoader
     */
    private $yml_loader;

    /**
     * List of enum classes for which we've already defined default values.
     *
     * @var string[]
     */
    private $handled_enum_classes = [];

    /**
     * @param YamlFileLoader $yml_loader
     */
    public function __construct(YamlFileLoader $yml_loader)
    {
        $this->yml_loader = $yml_loader;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $locale, $domain = 'messages')
    {
        if (!class_exists($domain)) {
            throw new \RuntimeException(sprintf("Could not load constants for a class '%s'.", $domain));
        }

        $messages = [];
        if (! in_array($domain, $this->handled_enum_classes)) {
            $messages                     = $this->loadFromConstants($domain);
            $this->handled_enum_classes[] = $domain;
        }

        $translations = $this->yml_loader->load($resource, $locale);

        foreach ($this->getStringConstants($domain) as $const => $value) {
            $key = $this->getPrettyName($domain) . '.' . strtolower($const);
            if ($translations->has($key)) {
                $messages[(string)$value] = $translations->get($key);
            }
        }

        $catalogue = new MessageCatalogue($locale);
        $catalogue->add($messages, $domain);

        return $catalogue;
    }

    /**
     * @param string $domain
     * @return array
     */
    private function loadFromConstants($domain)
    {
        $messages = [];
        foreach ($this->getStringConstants($domain) as $const => $value) {
            $messages[(string) $value] = strtolower($const);
        }
        return $messages;
    }

    /**
     * Get all the constants from the given class that are not arrays.
     *
     * @param string $domain
     * @return array
     */
    private function getStringConstants($domain)
    {
        $consts = (new \ReflectionClass($domain))->getConstants();

        return array_filter($consts, function ($element) {
            return !is_array($element);
        });
    }

    /**
     * Convert the fully qualified namespace into a lower case key.
     *
     * For instance:
     *     Hostnet\Order\Entity\OrderStatus will become hostnet.order.entity.order_status
     *
     * @param string $class_name
     * @return string
     */
    private function getPrettyName($class_name)
    {
        $class_name = implode('.', array_map('lcfirst', explode('\\', $class_name)));

        return strtolower(preg_replace_callback('/([A-Z]{1})/', function ($m) {
            return '_' . $m[0];
        }, $class_name));
    }
}
