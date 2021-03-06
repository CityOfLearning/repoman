<?php
/**
 * The name of the controller is based on the action (home) and the
 * namespace. This home controller is loaded by default because of
 * our IndexManagerController.
 */
class RepomanAjaxManagerController extends RepomanManagerController {
    /** @var bool Set to false to prevent loading of the header HTML. */
    public $loadHeader = false;
    /** @var bool Set to false to prevent loading of the footer HTML. */
    public $loadFooter = false;
    /** @var bool Set to false to prevent loading of the base MODExt JS classes. */
    public $loadBaseJavascript = false;
    
    public $props = array();
    
    /**
     * Any specific processing we want to do here. Return a string of html.
     * @param array $scriptProperties
     *      'namespace'
     *      'f' 
     */
    public function process(array $scriptProperties = array()) {
        $namespace = $this->modx->getOption('namespace', $scriptProperties);
        $function = $this->modx->getOption('f', $scriptProperties);
        $response = array();
        $response['msg'] = '';
        $response['success'] = true;

        
        try {
            $dir = Repoman::get_dir(MODX_BASE_PATH.$this->modx->getOption('repoman.dir'));
            $pkg_root_dir = $dir.'/'.$namespace;
            $config = Repoman::load_config($pkg_root_dir);
            
            $Repoman = new Repoman($this->modx, $config);
            
            switch ($function) {
                case 'update':
                    $Repoman->update($pkg_root_dir);
                    $response['msg'] = $config['package_name'].' updated successfully to version '.$config['version'].'-'.$config['release'];
                    break;
                case 'install':
                    $Repoman->install($pkg_root_dir);
                    $response['msg'] = $config['package_name'].' installed successfully!';
                    break;
                case 'uninstall':
                    $Repoman->uninstall($pkg_root_dir);
                    $response['msg'] = $config['package_name'].' uninstalled successfully!';
                    break;
                case 'build':
                    $Repoman->build($pkg_root_dir);
                    $response['msg'] = $config['package_name'].' should have been built successfully.  Look inside the core/packages/ directory for the file. Please use the command line tool if you need to monitor the error log troubleshoot this process.';
                    break;

                    break;
                default:
                    $response['success'] = false;
                    $response['msg'] = 'Unknown function: '.$function;
            }            
        }
        catch (Exception $e) {
            $response['success'] = false;
            $response['msg'] = $e->getMessage();
        }
        
        return json_encode($response);
    }
    
    /**
     * The pagetitle to put in the <title> attribute.
     * @return null|string
     */
    public function getPageTitle() {
        return 'Repoman Ajax';
    }
    
}
/*EOF*/