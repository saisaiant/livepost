<?php

namespace App\Helpers\Routes;

class RouteHelper {
    public static function includeRouteFile(string $folder)
    {
        // iterate through the v1 folder recursively
        $dirIterator = new \RecursiveDirectoryIterator($folder);

        /** @var \RecursiveDirectoryIterator | RecursiveIteratorIterator $it */
        $it = new \RecursiveIteratorIterator($dirIterator);
        // require the file in each iteration
        while ($it->valid()){
            if(!$it->isDot() 
                && $it->isFile() 
                && $it->isReadable() 
                && $it->current()) 
                {
                    require $it->key();
                    // require $it->current()->getPathname();
                }
            $it->next();
        }
    }
}