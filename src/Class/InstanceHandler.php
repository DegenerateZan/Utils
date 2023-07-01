<?php

namespace DegenerateZan\Utils\Classes;

class InstanceHandler {
    public static function getInstanceMethods(object|string $instance): string {
        $reflection = new \ReflectionClass($instance);
        $methods = $reflection->getMethods();

        $output = '';
        foreach ($methods as $method) {
            $visibility = self::getMethodVisibility($method);
            $output .= $visibility . ' ' . $method->getName() . "\n";
        }

        return $output;
    }

    private static function getMethodVisibility(\ReflectionMethod $method): string {
        if ($method->isPublic()) {
            return '+';
        } elseif ($method->isProtected()) {
            return '#';
        } elseif ($method->isPrivate()) {
            return '-';
        } else {
            return '';
        }
    }


    public static function saveInvoke($classname_or_object, string $methodName, bool $status = false) {
        if (is_object($classname_or_object)) {
            $reflection = new \ReflectionObject($classname_or_object);
        } elseif (is_string($classname_or_object) && class_exists($classname_or_object)) {
            $reflection = new \ReflectionClass($classname_or_object);
        } else {
            return false; // Invalid class or object provided
        }

        if ($reflection->hasMethod($methodName)) {
            $method = $reflection->getMethod($methodName);

            if ($method->isPublic()) {
                if ($method->isStatic()) {
                    $result = $method->invoke(null);
                } else {
                    $result = $method->invoke($classname_or_object);
                }

                if ($status && $method->hasReturnType() && $method->getReturnType()->getName() == 'void') {
                    return true; // Method does not have a return value
                } else {
                    return $result;
                }
            } else {
                return 'Method is not accessible'; // Method is not public
            }
        } else {
            return 'Method does not exist'; // Method does not exist
        }
    }
}
