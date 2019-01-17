<?php

class Permission {

    /**
     * @var array
     */
    protected $results = [];

    /**
     * Set the result array permissions and errors.
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->results['permissions'] = [];

        $this->results['errors'] = null;
    }

    /**
     * Check for the folders permissions.
     *
     * @param array $folders
     * @return array
     */
    public function check()
    {
        $folders = [
            // 'permissions' => [
                'storage/framework/'     => '775',
                'storage/logs/'          => '775',
                'bootstrap/cache/'       => '775'
            // ]
        ];

        foreach($folders as $folder => $permission)
        {
            if (!($this->getPermission($folder) >= $permission))
            {
                $this->addFileAndSetErrors($folder, $permission, false);
            }
            else {
                $this->addFile($folder, $permission, true);
            }
        }

        return $this->results;
    }

    /**
     * Get a folder permission.
     *
     * @param $folder
     * @return string
     */
    private function getPermission($folder)
    {
        $location = str_replace('\\', '/', getcwd());
        $currentLocation = explode("/", $location);
        array_pop($currentLocation);
        array_pop($currentLocation);
        $desiredLocation = implode("/", $currentLocation);
        $fileLocation = $desiredLocation . '/' .$folder;

        return substr(sprintf('%o', fileperms($fileLocation)), -4);
    }

    /**
     * Add the file to the list of results.
     *
     * @param $folder
     * @param $permission
     * @param $isSet
     */
    private function addFile($folder, $permission, $isSet)
    {
        array_push($this->results['permissions'], [
            'folder' => $folder,
            'permission' => $permission,
            'isSet' => $isSet
        ]);
    }

    /**
     * Add the file and set the errors.
     *
     * @param $folder
     * @param $permission
     * @param $isSet
     */
    private function addFileAndSetErrors($folder, $permission, $isSet)
    {
        $this->addFile($folder, $permission, $isSet);

        $this->results['errors'] = true;
    }

    /**
     * Render view for class.
     *
     */
    public function render()
    {
        ob_start();

        $permissions = $this->check();

        include __DIR__ . '/../Views/permission.blade.php';

        return ob_get_clean();
    }
}