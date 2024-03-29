<?php
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class AppKernel
 */
class AppKernel extends Kernel
{
    /**
     * registering bundles
     */
    public function registerBundles()
    {
        // When you install a third-party bundle or create a new bundle in your
        // application, you must add it in the following array to register it
        // in the application. Otherwise, the bundle won't be enabled and you
        // won't be able to use it.
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Ivory\GoogleMapBundle\IvoryGoogleMapBundle(),
            new Ivory\SerializerBundle\IvorySerializerBundle(),
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            new Anysrv\RecaptchaBundle\AnysrvRecaptchaBundle(),
            new Project\DefaultBundle\ProjectDefaultBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Http\HttplugBundle\HttplugBundle(),
            new Fhm\FhmBundle\FhmFhmBundle(),
            new Fhm\UserBundle\FhmUserBundle(),
            new Fhm\GeolocationBundle\FhmGeolocationBundle(),
            new Fhm\ContactBundle\FhmContactBundle(),
            new Fhm\NewsBundle\FhmNewsBundle(),
            new Fhm\EventBundle\FhmEventBundle(),
            new Fhm\PartnerBundle\FhmPartnerBundle(),
            new Fhm\ArticleBundle\FhmArticleBundle(),
            new Fhm\NewsletterBundle\FhmNewsletterBundle(),
            new Fhm\MediaBundle\FhmMediaBundle(),
            new Fhm\GalleryBundle\FhmGalleryBundle(),
            new Fhm\SliderBundle\FhmSliderBundle(),
            new Fhm\MailBundle\FhmMailBundle(),
            new Fhm\NotificationBundle\FhmNotificationBundle(),
            new Fhm\TestimonyBundle\FhmTestimonyBundle(),
            new Fhm\CardBundle\FhmCardBundle(),
            new Fhm\NoteBundle\FhmNoteBundle(),
            new Fhm\HistoricBundle\FhmHistoricBundle(),
        ];
        // Some bundles are only used while developing the application or during
        // the unit and functional tests. Therefore, they are only registered
        // when the application runs in 'dev' or 'test' environments. This allows
        // to increase application performance in the production environment.
        if (in_array($this->getEnvironment(), ['dev', 'test', getenv('DATABASE_MANAGER')])) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }
        if ($this->getEnvironment() == 'odm') {
            $bundles[] = new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle();
        }

        return $bundles;
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return __DIR__;
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    /**
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/environments/config_'.$this->getEnvironment().'.yml');
    }
}
