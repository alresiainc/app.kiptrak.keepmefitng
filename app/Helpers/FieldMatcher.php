<?php

namespace App\Helpers;



class FieldMatcher
{
    /**
     * Match required fields to form fields with fuzzy matching, including joined words.
     *
     * @param array $requiredFields - An associative array of required fields and their variations.
     * @param array $formFields - An associative array of form fields submitted (key-value pairs).
     * @return array - An associative array with matched fields and their corresponding values.
     */
    public function matchFields(array $requiredFields, array $formFields)
    {
        $matchedFields = [];

        // Normalize form fields by converting them to lower case and handling joined words
        $normalizedFormFields = $this->normalizeFormFields($formFields);

        // Loop through each required field and its variations
        foreach ($requiredFields as $mainField => $variations) {
            // If the required field is just a string (no variations provided)
            if (is_int($mainField)) {
                $mainField = $variations; // Set the main field name
                $variations = [$mainField]; // Use the main field as the only variation
            } elseif (is_string($variations)) {
                // If the value is a string, convert it to an array with just that string
                $variations = [$variations];
            }

            $foundMatch = false;

            // Normalize variations for better matching
            $normalizedVariations = $this->normalizeVariations($variations);

            // Exact match search for any variation in the normalized form fields
            foreach ($normalizedVariations as $normalizedVariation) {
                if (array_key_exists($normalizedVariation, $normalizedFormFields)) {
                    $matchedFields[$mainField] = $normalizedFormFields[$normalizedVariation];
                    $foundMatch = true;
                    break; // Stop once the first exact match is found
                }
            }

            // If no exact match is found, try fuzzy matching
            if (!$foundMatch) {
                $bestMatch = null;
                $highestSimilarity = 0;

                foreach ($normalizedFormFields as $formFieldKey => $formFieldValue) {
                    foreach ($normalizedVariations as $normalizedVariation) {
                        // Calculate the similarity between the variation and the normalized form field key
                        similar_text($normalizedVariation, $formFieldKey, $similarity);

                        // If similarity is higher than the previous highest and above a certain threshold (e.g., 60%)
                        if ($similarity > $highestSimilarity && $similarity > 60) {
                            $highestSimilarity = $similarity;
                            $bestMatch = $formFieldKey;
                        }
                    }
                }

                // If a fuzzy match is found, use it; otherwise, set it to null
                if ($bestMatch) {
                    $matchedFields[$mainField] = $normalizedFormFields[$bestMatch];
                } else {
                    $matchedFields[$mainField] = null;
                }
            }
        }

        return $matchedFields;
    }

    /**
     * Normalize form fields to handle different word patterns (e.g., "first_name", "firstname").
     *
     * @param array $formFields - The form fields to normalize.
     * @return array - The normalized form fields.
     */
    private function normalizeFormFields(array $formFields)
    {
        $normalized = [];

        foreach ($formFields as $key => $value) {
            $normalizedKey = $this->normalizeField($key);
            $normalized[$normalizedKey] = $value;
        }

        return $normalized;
    }

    /**
     * Normalize variations for easier comparison (e.g., "first_name" -> "firstname").
     *
     * @param array $variations - The variations to normalize.
     * @return array - The normalized variations.
     */
    private function normalizeVariations(array $variations)
    {
        return array_map([$this, 'normalizeField'], $variations);
    }

    /**
     * Normalize a field by converting to lower case and handling joined words.
     *
     * @param string $field - The field to normalize.
     * @return string - The normalized field.
     */
    private function normalizeField($field)
    {
        // Convert to lower case
        $normalized = strtolower($field);

        // Remove underscores and spaces to create a joined version (e.g., "first_name" -> "firstname")
        $normalized = str_replace(['_', ' '], '', $normalized);

        return $normalized;
    }
}
