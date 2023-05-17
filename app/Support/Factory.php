<?php

namespace Skeleton\Support;

use Symfony\Component\Finder\Finder;

class Factory
{
    protected static string $baseNamespace = '\\Skeleton\\';

    public static function run(string $directory, string $name = '*')
    {
        $isLookingForSpecificIntegration = $name !== '*';
        $baseClass = substr(basename($directory), 0, -1);

        if (! file_exists(app_path($directory))) {
            return [];
        }

        $finder = Finder::create()
            ->in(app_path($directory))
            ->name("$name.php")
            ->notName("$baseClass.php")
            ->files();

        $namespace = self::$baseNamespace . str_replace('/', '\\', $directory);
        $result = [];

        foreach ($finder as $file) {
            $class = self::resolveClass($directory, $file->getPathname());

            if (! class_exists($class)) {
                continue;
            }

            if (! is_subclass_of($class, "$namespace\\$baseClass")) {
                if (! $isLookingForSpecificIntegration) {
                    throw new \Exception($baseClass . ' must be an instance of ' . "$namespace\\$baseClass");
                }
            }

            $result[] = new $class;
        }

        return $result;
    }

    private static function resolveClass(string $directory, string $path)
    {
        // Get the Class namespace
        $class = str_replace(app_path($directory), '', $path);
        $class = str_replace('.php', '', $class);
        $class = str_replace('/', '\\', $class);

        // Get the Base namespace
        $namespace = self::$baseNamespace . str_replace('/', '\\', $directory);

        // Get fully qualified class name
        $fqcn = vsprintf('%s%s', [
            $namespace,
            $class,
        ]);

        if (! class_exists($fqcn)) {
            throw new \Exception("Class {$fqcn} does not exist");
        }

        return vsprintf('%s%s', [
            $namespace,
            $class,
        ]);
    }
}
