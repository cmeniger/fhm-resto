<?php
namespace Fhm\FhmBundle\Services;

use Fhm\FhmBundle\Controller\FhmController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * Class Schedules
 *
 * @package Fhm\FhmBundle\Services
 */
class Schedules extends FhmController
{
    protected $data;
    protected $data_implode;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container    = $container;
        $this->data         = array();
        $this->data_implode = array();
        parent::__construct();
    }

    /**
     * @return array
     */
    public function getDataImplode()
    {
        return $this->data_implode;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data         = $data;
        $this->data_implode = $this->entityToForm($data);

        return $this;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getValue($key = '')
    {
        if($key)
        {
            return isset($this->data_implode[$key]) ? $this->data_implode[$key] : null;
        }

        return $this->container->get('templating')->render
        (
            '::FhmFhm/Template/schedules.html.twig',
            array
            (
                'data'          => $this->data,
                'dataImplode'   => $this->data_implode,
                'dataFormatted' => $this->_format()
            )
        );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setValue($key, $value)
    {
        if($key == 'close_date' && is_string($value))
        {
            $d     = substr($value, 0, 2);
            $m     = substr($value, 3, 2);
            $y     = substr($value, 6, 4);
            $h     = substr($value, 10);
            $value = new \DateTime($y . '/' . $m . '/' . $d . ' ' . $h);
        }
        if($key == 'close_enable')
        {
            $value = $value == 0 ? false : true;
        }
        $this->data_implode[$key] = $value;
        $this->data               = $this->formToEntity($this->data_implode);

        return $this;
    }

    /**
     * @param $array
     *
     * @return $this
     */
    public function setValues($array)
    {
        foreach($array as $key => $value)
        {
            $this->setValue($key, $value);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        if($this->getValue('close_enable'))
        {
            $state = 'close';
        }
        else
        {
            $now      = new \DateTime();
            $day      = $now->format('N');
            $hours    = $now->format('G');
            $minutes  = $now->format('i');
            $formated = $hours . $minutes;
            $datas    = $this->_formatState($day);
            $state    = $datas ? 'close' : 'nodata';
            foreach($datas as $data)
            {
                if(is_array($data))
                {
                    $state = $formated >= $data[0] && $formated < $data[1] ? 'open' : $state;
                }
                else
                {
                    $state = $state != 'open' ? $data : $state;
                }
            }
        }

        return $state;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function entityToForm($data)
    {
        if(isset($data['close']) && isset($data['close']['date']))
        {
            $data['close']['date'] = new \DateTime($data['close']['date']['date']);
        }
        $transform = array();
        foreach($data as $key => $value)
        {
            $transform = array_merge($transform, $this->_arrayImplode($value, $key));
        }

        return $transform;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function formToEntity($data)
    {
        $transform = $this->_arrayExplode($data);

        return $transform;
    }

    /**
     * @param        $data
     * @param string $prefix
     *
     * @return array
     */
    private function _arrayImplode($data, $prefix = '')
    {
        $list = array();
        if(is_array($data))
        {
            foreach($data as $key => $value)
            {
                $list = array_merge($list, $this->_arrayImplode($value, $prefix . '_' . $key));
            }
        }
        else
        {
            $list[$prefix] = $data;
        }

        return $list;
    }

    /**
     * @param $data
     *
     * @return array
     */
    private function _arrayExplode($data)
    {
        $list = array();
        foreach($data as $key => $value)
        {
            $new     = array();
            $current = &$new;
            $paths   = explode('_', $key);
            foreach($paths as $path)
            {
                $current = &$current[$path];
            }
            $current = $value;
            $list    = array_replace_recursive($list, $new);
        }

        return $list;
    }

    /**
     * @return array
     */
    private function _format()
    {
        $formatted  = array('days' => array());
        $transClose = $this->container->get('translator')->trans('fhm.schedules.day.close', array(), 'FhmFhmBundle');
        if(isset($this->data['days']))
        {
            foreach($this->data['days'] as $key => $value)
            {
                $am = '-';
                $pm = '-';
                if((isset($value[0]) && $value[0] == 'close') || (isset($value[1]) && $value[1] == 'close'))
                {
                    $am = $transClose;
                }
                elseif(isset($value[0]) && isset($value[1]) && $value[0] != '' && $value[1] != '')
                {
                    $am = $this->container->get('translator')->trans('fhm.schedules.day.hour', array('%start%' => $value[0], '%end%' => $value[1]), 'FhmFhmBundle');
                }
                if((isset($value[2]) && $value[2] == 'close') || (isset($value[3]) && $value[3] == 'close'))
                {
                    $pm = $transClose;
                }
                elseif(isset($value[2]) && isset($value[3]) && $value[2] != '' && $value[3] != '')
                {
                    $pm = $this->container->get('translator')->trans('fhm.schedules.day.hour', array('%start%' => $value[2], '%end%' => $value[3]), 'FhmFhmBundle');
                }
                $all                     = ($am == $pm) ? $am : ($am == '-' ? $pm : ($pm == '-' ? $am : ''));
                $formatted['days'][$key] = array(
                    'label'    => $this->container->get('translator')->trans('fhm.schedules.days.' . $key, array(), 'FhmFhmBundle'),
                    'am'       => $am,
                    'pm'       => $pm,
                    'all'      => $all,
                    'classAM'  => $am == $transClose ? 'closed' : ($am == '-' ? 'empty' : ''),
                    'classPM'  => $pm == $transClose ? 'closed' : ($pm == '-' ? 'empty' : ''),
                    'classALL' => $all == $transClose ? 'closed' : ($all == '-' ? 'empty' : '')
                );
            }
        }
        $formatted['close_enable'] = $this->getValue('close_enable');
        $formatted['close_date']   = $this->getValue('close_date');
        $formatted['close_reason'] = $this->getValue('close_reason');

        return $formatted;
    }

    /**
     * @return array
     */
    private function _formatState($day)
    {
        $formatted = array();
        if(isset($this->data['days'][$day - 1]))
        {
            $formattedDay   = $this->data['days'][$day - 1];
            $formattedValue = array(
                isset($formattedDay[0]) && $formattedDay[0] != '' && $formattedDay[0] != 'close' ? str_replace(':', '', $formattedDay[0]) : '',
                isset($formattedDay[1]) && $formattedDay[1] != '' && $formattedDay[1] != 'close' ? str_replace(':', '', $formattedDay[1]) : '',
                isset($formattedDay[2]) && $formattedDay[2] != '' && $formattedDay[2] != 'close' ? str_replace(':', '', $formattedDay[2]) : '',
                isset($formattedDay[3]) && $formattedDay[3] != '' && $formattedDay[3] != 'close' ? str_replace(':', '', $formattedDay[3]) : ''
            );
            if($formattedValue[0] != '' && $formattedValue[1] != '' && $formattedValue[0] > $formattedValue[1])
            {
                $formatted[] = array('0000', $formattedValue[1]);
            }
            if($formattedValue[2] != '' && $formattedValue[3] != '' && $formattedValue[2] > $formattedValue[3])
            {
                $formatted[] = array('0000', $formattedValue[3]);
            }
        }
        if(isset($formattedDay))
        {
            $am             = 'nodata';
            $pm             = 'nodata';
            $formattedDay   = $this->data['days'][$day];
            $formattedValue = array(
                isset($formattedDay[0]) && $formattedDay[0] != '' && $formattedDay[0] != 'close' ? str_replace(':', '', $formattedDay[0]) : '',
                isset($formattedDay[1]) && $formattedDay[1] != '' && $formattedDay[1] != 'close' ? str_replace(':', '', $formattedDay[1]) : '',
                isset($formattedDay[2]) && $formattedDay[2] != '' && $formattedDay[2] != 'close' ? str_replace(':', '', $formattedDay[2]) : '',
                isset($formattedDay[3]) && $formattedDay[3] != '' && $formattedDay[3] != 'close' ? str_replace(':', '', $formattedDay[3]) : ''
            );
            if(isset($formattedDay[0]) && isset($formattedDay[1]))
            {
                if($formattedDay[0] == 'close' || $formattedDay[1] == 'close')
                {
                    $am = 'close';
                }
                elseif($formattedValue[0] != '' && $formattedValue[1] != '')
                {
                    if($formattedValue[0] > $formattedValue[1])
                    {
                        $am = array($formattedValue[0], '2400');
                    }
                    else
                    {
                        $am = array($formattedValue[0], $formattedValue[1]);
                    }
                }
            }
            if(isset($formattedDay[2]) && isset($formattedDay[3]))
            {
                if($formattedDay[2] == 'close' || $formattedDay[3] == 'close')
                {
                    $pm = 'close';
                }
                elseif($formattedValue[2] != '' && $formattedValue[2] != '')
                {
                    if($formattedValue[2] > $formattedValue[3])
                    {
                        $pm = array($formattedValue[2], '2400');
                    }
                    else
                    {
                        $pm = array($formattedValue[2], $formattedValue[3]);
                    }
                }
            }
            if($am == 'nodata' && $pm == 'nodata')
            {
                $formatted[] = 'nodata';
            }
            elseif($am == 'nodata')
            {
                $formatted[] = $pm;
            }
            elseif($pm == 'nodata')
            {
                $formatted[] = $am;
            }
            else
            {
                $formatted[] = $am;
                $formatted[] = $pm;
            }
        }

        return $formatted;
    }
}