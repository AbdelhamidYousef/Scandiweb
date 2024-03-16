<?php

namespace Frame\Validation;

use Exception;
use Frame\Exceptions\ValidationException;

class Validator
{
    /**
     * The errors that have occurred during validation.
     * 
     * @var array<string, array>
     */
    private array $errors = [];

    /**
     * Validate the given data against the given rules.
     * 
     * @param array $data
     * @param array $rules
     * @return void
     */
    public function validate(array $data, array $fieldsRules)
    {
        foreach ($fieldsRules as $field => $rules) {
            if (!isset($data[$field])) {
                $this->errors[$field][] = 'This field is required.';
                continue;  // Skip validation for this field.
            }

            foreach ($rules as $rule) {
                [$rule, $params] = $this->parseRule($rule);

                $ruleInstance = $this->createRule($rule);

                if (!$ruleInstance->validate($data, $field, $params)) {
                    $this->errors[$field][] = $ruleInstance->message($data, $field, $params);
                }
            }
        }
    }

    /**
     * Parse a rule string to get the rule name and parameters
     * 
     * @param string $rule
     * @return array [$ruleName, $params]
     */
    private function parseRule(string $rule)
    {
        $fragments = explode(':', $rule);

        $rule = $fragments[0];
        $params = isset($fragments[1]) ? array($fragments[1]) : [];

        return [$rule, $params];
    }

    /**
     * Create a new rule instance.
     * 
     * @param string $rule
     */
    private function createRule(string $rule)
    {
        $this->validateRule($rule);

        return new ($this->getFullClassName($rule));
    }

    /**
     * Validate that a rule is valid.
     * 
     * @param   string $rule
     * @return  $this
     * 
     * @throws  Exception
     */
    private function validateRule(string $rule): static
    {
        if (class_exists($this->getFullClassName($rule))) {
            return $this;
        }

        throw new Exception("The validation rule [$rule] isn't valid. Please make sure to provide a valid rule");
    }

    /**
     * Get the full class name of a rule.
     *
     * @param string $rule
     * @return string
     */
    private function getFullClassName(string $rule): string
    {
        return __NAMESPACE__ . '\\Rules\\' . ucfirst($rule);
    }

    /**
     * Checks if the validation has errors.
     * 
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get the errors that have occurred during validation.
     * 
     * @return array<string, array>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Add an error to the validation errors.
     * 
     * @param string $field
     * @param string $message
     */
    public function addError(string $field, string $message)
    {
        $this->errors[$field][] = $message;
    }
}
