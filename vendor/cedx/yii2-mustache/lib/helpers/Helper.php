<?php
/**
 * @file
 * Implementation of the `yii\mustache\helpers\Helper` class.
 */
namespace yii\mustache\helpers;

// Dependencies.
use yii\base\InvalidParamException;
use yii\base\Object;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Provides the abstract base class for a view helper.
 */
abstract class Helper extends Object {

  /**
   * @var string $argumentSeparator
   * String used to separate the arguments for helpers supporting the "two arguments" syntax.
   */
  public $argumentSeparator=':';

  /**
   * Returns the output sent by the call of the specified function.
   * @param callable $callback The function to invoke.
   * @return string The captured output.
   */
  protected function captureOutput($callback) {
    ob_start();
    call_user_func($callback);
    return ob_get_clean();
  }

  /**
   * Parses the arguments of a parametrized helper.
   * Arguments can be specified as a single value, or as a string in JSON format.
   * @param string $text The section content specifying the helper arguments.
   * @param string $defaultArgument The name of the default argument. This is used when the section content provides a plain string instead of a JSON object.
   * @param array $defaultValues The default values of arguments. These are used when the section content does not specify all arguments.
   * @return array The parsed arguments as an associative array.
   */
  protected function parseArguments($text, $defaultArgument, array $defaultValues=[]) {
    try {
      if(is_array($json=Json::decode($text))) return ArrayHelper::merge($defaultValues, $json);
      throw new InvalidParamException();
    }

    catch(InvalidParamException $e) {
      $defaultValues[$defaultArgument]=$text;
      return $defaultValues;
    }
  }
}
