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

        $consts       = (new \ReflectionClass($domain))->getConstants();
        $translations = $this->yml_loader->load($resource, $locale);
        $merged       = [];

        // Merge the translations in the translation file with the contact values.
        // So if we define a translation we will map that to the correct number
        // in the MessageCatalogue. Meaning we will have an array with the
        // constant value as key and the translation as values, or the constant
        // name if no translation was found.
        foreach ($consts as $const => $value) {
            $key = $this->getPrettyName($domain) . "." . strtolower($const);

            $merged[(string) $value] = $translations->has($key) ? $translations->get($key) : strtolower($const);
        }

        $catalogue = new MessageCatalogue($locale);
        $catalogue->add($merged, $domain);

        return $catalogue;
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
            return "_" . $m[0];
        }, $class_name));
    }
}
