<?php
namespace luya\base;

use yii;
use Exception;

/**
 *
 * @author nadar
 */
class Module extends \yii\base\Module
{
    /**
     *
     * @var array
     */
    public $requiredComponents = [];

    /**
     * This variable is only available if your not in a context call. A context call would be if the cms renders the module.
     * 
     * @var boolean
     */
    public $useAppLayoutPath = true;

    /**
     *
     * @var array
     */
    public $assets = [];

    /**
     * Contains the apis for each module to provided them in the admin module.
     * They represents the name of the api and the value represents the class.
     *
     * ```php
     * [
     *     'api-admin-user' => 'admin\apis\UserController',
     *     'api-cms-cat' => 'admin\apis\CatController'
     * ]
     * ```
     *
     * @var array
     */
    public static $apis = [];

    /**
     * Contains all urlRules for this module. Can't provided in key value pairing for pattern<=>route. must be array containing
     * class name or array with pattern, route informations.
     *
     * @var array
     */
    public static $urlRules = [];

    public $context = null;

    public $contextOptions = [];
    
    public $moduleLayout = 'moduleLayout';
    
    /**
     *
     * @throws Exception
     */
    public function init()
    {
        parent::init();
        // verify all the components
        foreach ($this->requiredComponents as $component) {
            if (!Yii::$app->has($component)) {
                throw new Exception(sprintf('The required component "%s" is not registered in the configuration file', $component));
            }
        }
    }

    public function getLayoutPath()
    {
        if ($this->useAppLayoutPath) {
            $this->setLayoutPath('@app/views/'.$this->id.'/layouts');
        }

        return parent::getLayoutPath();
    }

    public function findControllerRoute($route)
    {
        $xp = explode("/", $route);
        foreach ($xp as $k => $v) {
            if ($k == 0 && $v == $this->id) {
                unset($xp[$k]);
            }
            if (empty($v)) {
                unset($xp[$k]);
            }
        }

        if (empty($xp)) {
            $xp[] = $this->defaultRoute;
        }

        return implode("/", $xp);
    }

    public function setContext($name)
    {
        $this->context = $name;
    }

    public function getContext()
    {
        return $this->context;
    }
    
    public function setContextOptions(array $options)
    {
        $this->contextOptions = $options;
    }
    
    public function getContextOptions()
    {
        return $this->contextOptions;
    }
}
