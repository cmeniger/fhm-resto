<?php
namespace Fhm\FhmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;

class FhmController extends Controller
{
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

    /**
     *
     */
    public function __construct()
    {
        $this->repository       = 'FhmFhmBundle:Fhm';
        $this->csvDelimiter     = ';';
        $this->grouping         = '';
        $this->section          = 'Front';
        $this->parent           = false;
        $this->language         = false;
        $this->language_disable = false;
        $this->pagination       = null;
        $this->sort             = array('order', 'asc');
    }

    /**
     * @param $document
     *
     * @return $this
     */
    protected function historic($document)
    {
        $class = $this->class . 'Historic';
        if(class_exists($class))
        {
            $session = $this->get('session');
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
    protected function historicAdd($document, $object = false)
    {
        $class = $this->class . 'Historic';
        if(class_exists($class))
        {
            $session  = $this->get('session');
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
    protected function historicData($document)
    {
        $class = $this->class . 'Historic';
        if(class_exists($class))
        {
            $request        = $this->get('request');
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
    protected function historicPagination($document)
    {
        $class = $this->class . 'Historic';
        if(class_exists($class))
        {
            $request                  = $this->get('request');
            $dataPagination           = $request->get('FhmPagination');
            $instance                 = $this->instanceData();
            $pagination               = $this->setPagination($this->getParameter(array('historic', 'page'), 'fhm_fhm'), $this->getParameter(array('historic', 'left'), 'fhm_fhm'), $this->getParameter(array('historic', 'right'), 'fhm_fhm'))->getPagination($request->isXmlHttpRequest() ? $dataPagination['pagination'] : 1, count((array) $this->historicData($document)), $this->dmRepository($this->repository . 'Historic')->getHistoricCount($document, $instance->grouping->filtered, $instance->user->super), 'pagination', array(), $this->generateUrl($this->container->get('request')->get('_route'), array('id' => $document->getId())));
            $pagination->idData       = "content_data_historic";
            $pagination->idPagination = "content_pagination_historic";
            $pagination->counter      = $this->get('translator')->trans('fhm.historic.pagination.counter', array('%count%' => $pagination->page, '%all%' => $pagination->count), 'FhmFhmBundle');

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
    protected function routeExists($name)
    {
        $router = $this->container->get('router');

        return ($router->getRouteCollection()->get($name) === null) ? false : true;
    }

    /**
     * @param string $document
     *
     * @return \stdClass
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function instanceData($document = "")
    {
        $this->initLanguage();
        $fhmExtension      = new \Fhm\FhmBundle\Twig\FhmExtension($this->container);
        $roleSuperAdmin    = $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        $roleAdmin         = $this->get('security.context')->isGranted('ROLE_ADMIN');
        $roleModerator     = $this->get('security.context')->isGranted('ROLE_MODERATOR');
        $groupingCurrent   = $this->grouping ? $this->grouping : $this->get($this->getParameter("grouping", "fhm_fhm"))->getGrouping();
        $groupingUsed      = $groupingCurrent;
        $groupingUsed      = $roleModerator ? $this->getUser()->getFirstGrouping() : $groupingUsed;
        $groupingUsed      = $roleAdmin ? "" : $groupingUsed;
        $groupingFiltered  = $groupingCurrent;
        $groupingFiltered  = $roleModerator ? $this->getUser()->getGrouping() : $groupingFiltered;
        $groupingFiltered  = $roleAdmin ? "" : $groupingFiltered;
        $groupingAvailable = $this->get($this->getParameter("grouping", "fhm_fhm"))->getGroupingAvailable();
        $groupingAvailable = $roleModerator ? $this->getUser()->getGrouping() : $groupingAvailable;
        $groupingAvailable = $roleAdmin ? $this->get($this->getParameter("grouping", "fhm_fhm"))->getGroupingAvailable() : $groupingAvailable;
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
        $data->grouping->visible   = $this->get($this->getParameter("grouping", "fhm_fhm"))->getVisible();
        $data->user                = new \stdClass();
        $data->user->document      = $this->getUser();
        $data->user->super         = $roleSuperAdmin;
        $data->user->admin         = $roleAdmin;
        $data->user->moderator     = $roleModerator;
        $data->user->grouping      = $this->getUser() ? $this->getUser()->getGrouping() : '';
        // Error
        if($this->section == "Admin" && !$data->user->admin && !$data->user->moderator && $data->grouping->current != '' && !$data->user->document->hasGrouping($data->grouping->current))
        {
            throw new HttpException(403, $this->get('translator')->trans('fhm.error.forbidden', array(), 'FhmFhmBundle'));
        }

        return $data;
    }

    /**
     * @param        $datas
     * @param string $filename
     *
     * @return Response
     */
    protected function csvExport($datas, $filename = '')
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
    protected function formRename($name, $datas)
    {
        $post = array();
        foreach((array) $datas as $key => $value)
        {
            $post[$name . "[" . $key . "]"] = $value;
        }

        return $post;
    }

    /**
     * @param $bundle
     *
     * @return $this
     */
    protected function initForm($bundle)
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
            $session        = $this->container->get('session')->get('_locale');
            $used           = $session ? $session : $locale;
            $used           = $this->get('security.context')->isGranted('ROLE_MODERATOR') ? $this->getUser()->getLanguages() : $used;
            $used           = $this->get('security.context')->isGranted('ROLE_ADMIN') ? $this->container->get('session')->get('_localeAdmin') : $used;
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
     * @param $grouping
     *
     * @return $this
     */
    protected function setGrouping($grouping)
    {
        $this->grouping = $grouping;

        return $this;
    }

    /**
     * @param $parent
     *
     * @return $this
     */
    protected function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @param $language
     *
     * @return $this
     */
    protected function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    protected function setLanguageDisable($data)
    {
        $this->language_disable = $data;

        return $this;
    }

    /**
     * @param $section
     *
     * @return $this
     */
    protected function setSection($section)
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
    protected function getSort($field = "", $order = "", $post = "", $pagination = 'pagination', $path = "")
    {
        $post               = array_merge(
            (array) $post,
            array(
                "FhmPagination[" . $pagination . "]" => 1
            )
        );
        $sort               = new \stdClass();
        $sort->path         = $path ? $path : $this->generateUrl($this->container->get('request')->get('_route'));
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
    protected function setSort($field, $order = 'asc')
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
    protected function setPagination($page = null, $left = null, $right = null)
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
    protected function getPagination($page = 1, $countPage = 1, $countAll = 1, $tag = 'pagination', $post = array(), $path = "")
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
        $pagination->path         = $path ? $path : $this->generateUrl($this->container->get('request')->get('_route'));
        $pagination->current      = $page;
        $pagination->post         = json_encode($post);
        $pagination->page         = $countPage;
        $pagination->count        = $countAll;
        $pagination->counter      = $this->get('translator')->trans($this->translation[1] . '.pagination.counter', array('%count%' => $countPage, '%all%' => $pagination->count), $this->translation[0]);
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
    protected function getLastRoute()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
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
    protected function getAlias($id, $name, $repository = null)
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
    protected function getUnique($id, $name, $multiple = false, $repository = null, $length = 4)
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
    protected function getCollection($datas)
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
     * @return Array
     */
    protected function getList($datas)
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
    protected function getParameter($route, $parent)
    {
        $parameters = $this->container->getParameter($parent);
        $value      = $parameters;
        foreach((array) $route as $sub)
        {
            $value = $value[$sub];
        }

        return $value;
    }

    /**
     * @return mixed
     */
    protected function dm()
    {
        return $this->get('doctrine_mongodb')->getManager();
    }

    /**
     * @param null $repository
     *
     * @return mixed
     */
    protected function dmRepository($repository = null)
    {
        $this->initLanguage();

        return $this->dm()->getRepository(($repository == null) ? $this->repository : $repository)->setParent($this->parent)->setLanguage($this->language);
    }

    /**
     * @param $obj
     *
     * @return $this
     */
    protected function dmDetach(&$obj)
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
    protected function dmPersist(&$obj)
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
    protected function dmRemove(&$obj)
    {
        if($obj != "")
        {
            $this->dm()->remove($obj);
            $this->dm()->flush();
        }

        return $this;
    }
}