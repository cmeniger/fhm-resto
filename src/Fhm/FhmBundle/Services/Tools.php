<?php
namespace Fhm\FhmBundle\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class Tools
{
    protected $container;
    protected $source;
    protected $repository;
    protected $translation;
    protected $document;
    protected $class;
    protected $form;
    protected $route;
    protected $view;
    protected $csvDelimiter;
    protected $grouping;
    protected $section;
    protected $parent;
    protected $language;
    protected $language_disable;
    protected $pagination;
    protected $sort;
    protected $instance;
    protected $bundle;

    /**
     * Tools constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container        = $container;
        $this->repository       = 'FhmFhmBundle:Fhm';
        $this->csvDelimiter     = ';';
        $this->grouping         = '';
        $this->section          = 'Front';
        $this->parent           = false;
        $this->language         = false;
        $this->language_disable = false;
        $this->pagination       = null;
        $this->sort             = array('order', 'asc');
        $this->instance         = '';
    }

    /**
     * @param string $src
     * @param string $bundle
     * @param string $route
     * @param string $document
     * @param string $section
     *
     * @return array
     */
    public function initData($src = 'Fhm', $bundle = 'Fhm', $route = '', $document = '', $section = '')
    {
        $this->source      = strtolower($src);
        $this->repository  = $src . $bundle . 'Bundle:' . ($document ? $document : $bundle);
        $this->class       = $src . '\\' . $bundle . 'Bundle' . '\\Document\\' . ($document ? $document : $bundle);
        $this->document    = new $this->class();
        $this->translation = array($src . $bundle . 'Bundle', strtolower($bundle));
        $this->view        = $src . $bundle;
        $this->route       = $route;
        $this->bundle      = strtolower($bundle);
        $this->section     = ucfirst(strtolower($section));
        $this->initForm($src . '\\' . $bundle . 'Bundle');

        return array(
            'source'      => $this->source,
            'class'       => $this->class,
            'document'    => $this->document,
            'translation' => $this->translation,
            'route'       => $this->route,
            'form'        => $this->form
        );
    }

    /**
     * @param $bundle
     *
     * @return $this
     */
    public function initForm($bundle)
    {
        $folder                      = ($this->section == '') ? '' : $this->section . '\\';
        $this->form                  = new \stdClass();
        $this->form->type            = new \stdClass();
        $this->form->type->search    = (class_exists($bundle . '\\Form\\Type\\' . $folder . 'SearchType')) ? '\\' . $bundle . '\\Form\\Type\\' . $folder . 'SearchType' : '\\Fhm\\FhmBundle\\Form\\Type\\' . $folder . 'SearchType';
        $this->form->type->create    = (class_exists($bundle . '\\Form\\Type\\' . $folder . 'CreateType')) ? '\\' . $bundle . '\\Form\\Type\\' . $folder . 'CreateType' : '\\Fhm\\FhmBundle\\Form\\Type\\' . $folder . 'CreateType';
        $this->form->type->update    = (class_exists($bundle . '\\Form\\Type\\' . $folder . 'UpdateType')) ? '\\' . $bundle . '\\Form\\Type\\' . $folder . 'UpdateType' : '\\Fhm\\FhmBundle\\Form\\Type\\' . $folder . 'UpdateType';
        $this->form->type->export    = (class_exists($bundle . '\\Form\\Type\\' . $folder . 'ExportType')) ? '\\' . $bundle . '\\Form\\Type\\' . $folder . 'ExportType' : '\\Fhm\\FhmBundle\\Form\\Type\\' . $folder . 'ExportType';
        $this->form->type->import    = (class_exists($bundle . '\\Form\\Type\\' . $folder . 'ImportType')) ? '\\' . $bundle . '\\Form\\Type\\' . $folder . 'ImportType' : '\\Fhm\\FhmBundle\\Form\\Type\\' . $folder . 'ImportType';
        $this->form->handler         = new \stdClass();
        $this->form->handler->create = (class_exists($bundle . '\\Form\\Handler\\' . $folder . 'CreateHandler')) ? '\\' . $bundle . '\\Form\\Handler\\' . $folder . 'CreateHandler' : '\\Fhm\\FhmBundle\\Form\\Handler\\' . $folder . 'CreateHandler';
        $this->form->handler->update = (class_exists($bundle . '\\Form\\Handler\\' . $folder . 'UpdateHandler')) ? '\\' . $bundle . '\\Form\\Handler\\' . $folder . 'UpdateHandler' : '\\Fhm\\FhmBundle\\Form\\Handler\\' . $folder . 'UpdateHandler';
        $this->form->handler->export = (class_exists($bundle . '\\Form\\Handler\\' . $folder . 'ExportHandler')) ? '\\' . $bundle . '\\Form\\Handler\\' . $folder . 'ExportHandler' : '\\Fhm\\FhmBundle\\Form\\Handler\\' . $folder . 'ExportHandler';
        $this->form->handler->import = (class_exists($bundle . '\\Form\\Handler\\' . $folder . 'ImportHandler')) ? '\\' . $bundle . '\\Form\\Handler\\' . $folder . 'ImportHandler' : '\\Fhm\\FhmBundle\\Form\\Handler\\' . $folder . 'ImportHandler';

        return $this;
    }

    /**
     * @return $this
     */
    public function initLanguage()
    {
        $this->initLanguageDisable();
        if(!$this->language_disable && $this->getParameter(array('languages', 'codes'), 'fhm_fhm'))
        {
            $locale         = $this->getParameter(array(), 'locale');
            $session        = $this->getSession()->get('_locale');
            $used           = $session ? $session : $locale;
            $used           = $this->getContainer()->get('security.authorization_checker')->isGranted('ROLE_MODERATOR') ? $this->getUser()->getLanguages() : $used;
            $used           = $this->getContainer()->get('security.authorization_checker')->isGranted('ROLE_ADMIN') ? $this->getSession()->get('_localeAdmin') : $used;
            $this->language = $used;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function initLanguageDisable()
    {
        $exceptions = $this->getParameter(array('languages', 'exceptions'), 'fhm_fhm');
        if(in_array($this->view, (array) $exceptions))
        {
            $this->setLanguageDisable(true);
        }

        return $this;
    }

    /**
     * @param $document
     *
     * @return $this
     */
    public function historic($document)
    {
        $class = $this->class . 'Historic';
        if(class_exists($class))
        {
            $session = $this->getSession();
            $obj     = clone($document);
            if(!$session->get('historic_' . $document->getId()))
            {
                $session->set('historic_' . $document->getId(), $obj);
            }
        }

        return $this;
    }

    /**
     * @param      $document
     * @param bool $object
     *
     * @return $this
     */
    public function historicAdd($document, $object = false)
    {
        $class = $this->class . 'Historic';
        if(class_exists($class))
        {
            $session  = $this->getSession();
            $obj      = $object ? $document : $session->get('historic_' . $document->getId());
            $historic = new $class;
            $historic->historicMerge($this->dm(), $obj);
            $historic->setHistoricParent($document);
            $this->dmPersist($historic);
            $document->addHistoricSon($historic);
            $this->dmPersist($document);
            $session->remove('historic_' . $document->getId());
        }

        return $this;
    }

    /**
     * @param $document
     *
     * @return null|array
     */
    public function historicData($document)
    {
        $class = $this->class . 'Historic';
        if(class_exists($class))
        {
            $request        = $this->getContainer()->get('request');
            $instance       = $this->instanceData();
            $dataPagination = $request->get('FhmPagination');

            return $this->dmRepository($this->repository . 'Historic')->getHistoricIndex($document, $request->isXmlHttpRequest() ? $dataPagination['pagination'] : 1, $this->getParameter(array('historic', 'page'), 'fhm_fhm'), $instance->grouping->filtered, $instance->user->super);
        }
        else
        {
            return null;
        }
    }

    /**
     * @param $document
     *
     * @return null|\stdClass
     */
    public function historicPagination($document)
    {
        $class = $this->class . 'Historic';
        if(class_exists($class))
        {
            $request                  = $this->getContainer()->get('request');
            $dataPagination           = $request->get('FhmPagination');
            $instance                 = $this->instanceData();
            $pagination               = $this->setPagination($this->getParameter(array('historic', 'page'), 'fhm_fhm'), $this->getParameter(array('historic', 'left'), 'fhm_fhm'), $this->getParameter(array('historic', 'right'), 'fhm_fhm'))->getPagination($request->isXmlHttpRequest() ? $dataPagination['pagination'] : 1, count((array) $this->historicData($document)), $this->dmRepository($this->repository . 'Historic')->getHistoricCount($document, $instance->grouping->filtered, $instance->user->super), 'pagination', array(), $this->getUrl(null, array('id' => $document->getId())));
            $pagination->idData       = "content_data_historic";
            $pagination->idPagination = "content_pagination_historic";
            $pagination->counter      = $this->getContainer()->get('translator')->trans('fhm.historic.pagination.counter', array('%count%' => $pagination->page, '%all%' => $pagination->count), 'FhmFhmBundle');

            return $pagination;
        }
        else
        {
            return null;
        }
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function routeExists($name)
    {
        $router = $this->getContainer()->get('router');

        return ($router->getRouteCollection()->get($name) === null) ? false : true;
    }

    /**
     * @param string $document
     *
     * @return \stdClass
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function instanceData($document = "")
    {
        $this->initLanguage();
        $fhmExtension      = new \Fhm\FhmBundle\Twig\FhmExtension($this->getContainer());
        $roleSuperAdmin    = $this->getContainer()->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN');
        $roleAdmin         = $this->getContainer()->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
        $roleModerator     = $this->getContainer()->get('security.authorization_checker')->isGranted('ROLE_MODERATOR');
        $groupingCurrent   = $this->grouping ? $this->grouping : $this->getContainer()->get($this->getParameter("grouping", "fhm_fhm"))->getGrouping();
        $groupingUsed      = $groupingCurrent;
        $groupingUsed      = $roleModerator ? $this->getUser()->getFirstGrouping() : $groupingUsed;
        $groupingUsed      = $roleAdmin ? "" : $groupingUsed;
        $groupingFiltered  = $groupingCurrent;
        $groupingFiltered  = $roleModerator ? $this->getUser()->getGrouping() : $groupingFiltered;
        $groupingFiltered  = $roleAdmin ? "" : $groupingFiltered;
        $groupingAvailable = $this->getContainer()->get($this->getParameter("grouping", "fhm_fhm"))->getGroupingAvailable();
        $groupingAvailable = $roleModerator ? $this->getUser()->getGrouping() : $groupingAvailable;
        $groupingAvailable = $roleAdmin ? $this->getContainer()->get($this->getParameter("grouping", "fhm_fhm"))->getGroupingAvailable() : $groupingAvailable;
        $languageCurrent   = $this->language_disable ? false : $this->language;
        $languageFiltered  = $languageCurrent;
        $languageFiltered  = $roleModerator ? $this->getUser()->getLanguages() : $languageFiltered;
        $languageFiltered  = $roleAdmin ? "" : $languageFiltered;
        $languageAvailable = $this->getParameter(array("languages", "codes"), "fhm_fhm");
        $languageAvailable = $roleModerator ? $this->getUser()->getLanguages() : $languageAvailable;
        $languageAvailable = $roleAdmin ? $this->getParameter(array("languages", "codes"), "fhm_fhm") : $languageAvailable;
        foreach((array) $languageAvailable as $key => $value)
        {
            unset($languageAvailable[$key]);
            $languageAvailable[$value] = $fhmExtension->getCountry($value);
        }
        // Data
        $data                      = new \stdClass();
        $data->source              = $this->source;
        $data->section             = $this->section;
        $data->class               = $this->class;
        $data->route               = $this->route;
        $data->lastroute           = $this->getLastRoute();
        $data->domain              = $this->translation[0];
        $data->translation         = $this->translation[1];
        $data->language            = new \stdClass();
        $data->language->current   = $languageCurrent;
        $data->language->used      = $languageCurrent;
        $data->language->filtered  = $languageFiltered;
        $data->language->different = $document ? !$document->hasLanguage($languageCurrent) : false;
        $data->language->available = $languageAvailable;
        $data->language->visible   = $this->language_disable ? false : $this->getParameter(array("languages", "codes"), "fhm_fhm");
        $data->grouping            = new \stdClass();
        $data->grouping->current   = $groupingCurrent;
        $data->grouping->used      = $groupingUsed;
        $data->grouping->filtered  = $groupingFiltered;
        $data->grouping->different = $groupingUsed != '' && $document ? !$document->hasGrouping($groupingUsed) : false;
        $data->grouping->available = $groupingAvailable;
        $data->grouping->visible   = $this->getContainer()->get($this->getParameter("grouping", "fhm_fhm"))->getVisible();
        $data->user                = new \stdClass();
        $data->user->document      = $this->getUser();
        $data->user->super         = $roleSuperAdmin;
        $data->user->admin         = $roleAdmin;
        $data->user->moderator     = $roleModerator;
        $data->user->grouping      = $this->getUser() ? $this->getUser()->getGrouping() : '';
        // Error
        if($this->section == "Admin" && !$data->user->admin && !$data->user->moderator && $data->grouping->current != '' && !$data->user->document->hasGrouping($data->grouping->current))
        {
            throw new HttpException(403, $this->getContainer()->get('translator')->trans('fhm.error.forbidden', array(), 'FhmFhmBundle'));
        }
        var_dump($this->class . ' / ' . $this->route);

        return $data;
    }

    /**
     * @param        $datas
     * @param string $filename
     *
     * @return Response
     */
    public function csvExport($datas, $filename = '')
    {
        // Open file
        $fp = fopen('php://output', 'w');
        ob_start();
        // Copy line per line
        foreach($datas as $data)
        {
            fputcsv($fp, $data, $this->csvDelimiter);
        }
        // Close file
        fclose($fp);
        $csv = ob_get_clean();
        // Response
        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename=' . (($filename == '') ? date('YmdHis') . '_export.csv' : $filename));
        $response->setContent($csv);

        return $response;
    }

    /**
     * @param $name
     * @param $datas
     *
     * @return array
     */
    public function formRename($name, $datas)
    {
        $post = array();
        foreach((array) $datas as $key => $value)
        {
            $post[$name . "[" . $key . "]"] = $value;
        }

        return $post;
    }

    /**
     * @param $grouping
     *
     * @return $this
     */
    public function setGrouping($grouping)
    {
        $this->grouping = $grouping;

        return $this;
    }

    /**
     * @param $parent
     *
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @param $language
     *
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function setLanguageDisable($data)
    {
        $this->language_disable = $data;

        return $this;
    }

    /**
     * @param $section
     *
     * @return $this
     */
    public function setSection($section)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @param string $field
     * @param string $order
     * @param string $post
     * @param string $pagination
     * @param string $path
     *
     * @return \stdClass
     */
    public function getSort($field = "", $order = "", $post = "", $pagination = 'pagination', $path = "")
    {
        $post               = array_merge(
            (array) $post,
            array(
                "FhmPagination[" . $pagination . "]" => 1
            )
        );
        $sort               = new \stdClass();
        $sort->path         = $path ? $path : $this->getUrl();
        $sort->field        = $field ? $field : $this->sort[0];
        $sort->order        = $order ? $order : $this->sort[1];
        $sort->post         = json_encode($post);
        $sort->idData       = "content_data";
        $sort->idPagination = "content_pagination";

        return $sort;
    }

    /**
     * @param        $field
     * @param string $order
     *
     * @return $this
     */
    public function setSort($field, $order = 'asc')
    {
        if($field)
        {
            $this->sort = array($field, $order);
        }

        return $this;
    }

    /**
     * @param null $page
     * @param null $left
     * @param null $right
     *
     * @return $this
     */
    public function setPagination($page = null, $left = null, $right = null)
    {
        if(!$this->pagination)
        {
            if($this->section == "Front" || $this->section == "Api")
            {
                $this->pagination        = new \stdClass();
                $this->pagination->page  = !is_null($page) ? $page : $this->getParameter(array('pagination', 'front', 'page'), 'fhm_fhm');
                $this->pagination->left  = !is_null($left) ? $left : $this->getParameter(array('pagination', 'front', 'left'), 'fhm_fhm');
                $this->pagination->right = !is_null($right) ? $right : $this->getParameter(array('pagination', 'front', 'right'), 'fhm_fhm');
            }
            else
            {
                $this->pagination        = new \stdClass();
                $this->pagination->page  = !is_null($page) ? $page : $this->getParameter(array('pagination', 'admin', 'page'), 'fhm_fhm');
                $this->pagination->left  = !is_null($left) ? $left : $this->getParameter(array('pagination', 'admin', 'left'), 'fhm_fhm');
                $this->pagination->right = !is_null($right) ? $right : $this->getParameter(array('pagination', 'admin', 'right'), 'fhm_fhm');
            }
        }

        return $this;
    }

    /**
     * @param int    $page
     * @param int    $countPage
     * @param int    $countAll
     * @param string $tag
     * @param array  $post
     * @param string $path
     *
     * @return \stdClass
     */
    public function getPagination($page = 1, $countPage = 1, $countAll = 1, $tag = 'pagination', $post = array(), $path = "")
    {
        $this->setPagination();
        // Object
        $post                     = array_merge(
            (array) $post,
            array(
                "FhmSort[field]" => $this->sort[0],
                "FhmSort[order]" => $this->sort[1]
            )
        );
        $pagination               = new \stdClass();
        $pagination->path         = $path ? $path : $this->getUrl();
        $pagination->current      = $page;
        $pagination->post         = json_encode($post);
        $pagination->page         = $countPage;
        $pagination->count        = $countAll;
        $pagination->counter      = $this->getContainer()->get('translator')->trans($this->translation[1] . '.pagination.counter', array('%count%' => $countPage, '%all%' => $pagination->count), $this->translation[0]);
        $pagination->tag          = "FhmPagination[" . $tag . "]";
        $pagination->max          = $this->pagination->page > 0 ? ceil($pagination->count / $this->pagination->page) : 0;
        $pagination->section      = $this->section;
        $pagination->idData       = "content_data";
        $pagination->idPagination = "content_pagination";
        // Pagination
        $datas = array();
        $left  = $this->pagination->left;
        $right = $this->pagination->right;
        // Pagination - Page 1
        $obj          = new \stdClass();
        $obj->page    = 1;
        $obj->current = ($pagination->current == 1) ? true : false;
        $obj->text    = 1;
        $datas[]      = $obj;
        // Pagination - Separator
        if(($pagination->current - $left) > 2)
        {
            $obj          = new \stdClass();
            $obj->page    = ceil(($pagination->current - $left) / 2);
            $obj->current = false;
            $obj->text    = '...';
            $datas[]      = $obj;
        }
        // Pagination - Bloc current
        for($i = ($pagination->current - $left); $i <= ($pagination->current + $right); $i++)
        {
            if($i > 1 && $i < $pagination->max)
            {
                $obj          = new \stdClass();
                $obj->page    = $i;
                $obj->current = ($i == $pagination->current) ? true : false;
                $obj->text    = $i;
                $datas[]      = $obj;
            }
        }
        // Pagination - Separator
        if(($pagination->current + $right) < ($pagination->max - 1))
        {
            $obj          = new \stdClass();
            $obj->page    = floor(($pagination->max - ($pagination->current + $right)) / 2) + $pagination->current + $right;
            $obj->current = false;
            $obj->text    = '...';
            $datas[]      = $obj;
        }
        // Pagination - Page max
        if($pagination->max > 1)
        {
            $obj          = new \stdClass();
            $obj->page    = $pagination->max;
            $obj->current = ($pagination->current == $pagination->max) ? true : false;;
            $obj->text = $pagination->max;
            $datas[]   = $obj;
        }
        // Pagination - data
        $pagination->datas = $datas;

        return $pagination;
    }

    /**
     * @return mixed
     */
    public function getLastRoute()
    {
        $request = $this->getContainer()->get('request_stack')->getCurrentRequest();
        $referer = $request->headers->get('referer');
        $route   = ($referer && $request->getBaseUrl() == '') ? $referer : '';
        $route   = ($referer == '' && $request->getBaseUrl()) ? $request->getBaseUrl() : $route;
        $route   = ($referer && $request->getBaseUrl()) ? str_replace($request->getBaseUrl(), '', substr($referer, strpos($referer, $request->getBaseUrl()))) : $route;

        return $route;
    }

    /**
     * @param      $id
     * @param      $name
     * @param null $repository
     *
     * @return mixed|string
     */
    public function getAlias($id, $name, $repository = null)
    {
        $alias   = "";
        $unique  = false;
        $code    = 0;
        $replace = array(
            'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
            'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', '€' => 'e',
            'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
            'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'µ' => 'u',
            'Œ' => 'oe', 'œ' => 'oe',
            '$' => 's'
        );
        while($alias == "" || !$unique)
        {
            $alias  = $name;
            $alias  = strtr($alias, $replace);
            $alias  = preg_replace('#[^A-Za-z0-9]+#', '-', $alias);
            $alias  = trim($alias, '-');
            $alias  = strtolower($alias);
            $alias  = ($code > 0) ? $alias . '-' . $code : $alias;
            $unique = $this->dmRepository($repository)->isUnique($id, $alias);
            $code++;
        }

        return $alias;
    }

    /**
     * @param            $id
     * @param            $name
     * @param bool|false $multiple
     * @param null       $repository
     * @param int        $length
     *
     * @return string
     */
    public function getUnique($id, $name, $multiple = false, $repository = null, $length = 4)
    {
        $alias  = "";
        $unique = false;
        $code   = $multiple ? 1 : 0;
        while($alias == "" || !$unique)
        {
            $alias  = $name;
            $alias  = ($code > 0) ? $alias . '_' . str_pad($code, $length, '0', STR_PAD_LEFT) : $alias;
            $unique = $this->dmRepository($repository)->isUnique($id, $alias);
            $code++;
        }

        return $alias;
    }

    /**
     * @param $datas
     *
     * @return ArrayCollection
     */
    public function getCollection($datas)
    {
        $collection = new ArrayCollection();
        foreach($datas as $data)
        {
            $collection->add($data);
        }

        return $collection;
    }

    /**
     * @param $datas
     *
     * @return array
     */
    public function getList($datas)
    {
        $list = array();
        foreach($datas as $data)
        {
            if($data->getActive() && !$data->getDelete())
            {
                $list[$data->getId()] = $data;
            }
        }

        return $list;
    }

    /**
     * @param $route
     * @param $parent
     *
     * @return mixed
     */
    public function getParameter($route, $parent)
    {
        $parameters = $this->getContainer()->getParameter($parent);
        $value      = $parameters;
        foreach((array) $route as $sub)
        {
            $value = $value[$sub];
        }

        return $value;
    }

    /**
     * @param array $parameters
     * @param null  $route
     * @param null  $referenceType
     *
     * @return string
     */
    public function getUrl($route = null, $parameters = array(), $referenceType = null)
    {
        $route = $route == null ? $this->getContainer()->get('request')->get('_route') : $route;

        return $this->getContainer()->get('router')->generate($route, $parameters, $referenceType);
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->getContainer()->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    public function getSession()
    {
        return $this->getContainer()->get('session');
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param       $key
     * @param array $parameters
     * @param null  $domain
     *
     * @return string
     */
    public function trans($key, $parameters = array(), $domain = null)
    {
        $key    = $key[0] == '.' ? $this->translation[1] . $key : $key;
        $domain = $domain == null ? $this->translation[0] : $domain;

        return $this->getContainer()->get('translator')->trans($key, $parameters, $domain);
    }

    /**
     * @return mixed
     */
    public function dm()
    {
        return $this->getContainer()->get('doctrine_mongodb')->getManager();
    }

    /**
     * @param null $repository
     *
     * @return mixed
     */
    public function dmRepository($repository = null)
    {
        $this->initLanguage();

        return $this->dm()->getRepository(($repository == null) ? $this->repository : $repository)->setParent($this->parent)->setLanguage($this->language);
    }

    /**
     * @param $obj
     *
     * @return $this
     */
    public function dmDetach(&$obj)
    {
        if($obj != "")
        {
            $this->dm()->detach($obj);
        }

        return $this;
    }

    /**
     * @param $obj
     *
     * @return $this
     */
    public function dmPersist(&$obj)
    {
        if($obj != "")
        {
            $this->dm()->persist($obj);
            $this->dm()->flush();
        }

        return $this;
    }

    /**
     * @param $obj
     *
     * @return $this
     */
    public function dmRemove(&$obj)
    {
        if($obj != "")
        {
            $this->dm()->remove($obj);
            $this->dm()->flush();
        }

        return $this;
    }
}