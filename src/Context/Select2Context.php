<?php

namespace Novaway\CommonContexts\Context;

use Behat\Mink\Element\DocumentElement;

class Select2Context extends BaseContext
{
    /**
     * Fills in Select2 field with specified
     *
     * @When /^(?:|I )fill in select2 "(?P<field>(?:[^"]|\\")*)" with "(?P<value>(?:[^"]|\\")*)"$/
     * @When /^(?:|I )fill in select2 "(?P<value>(?:[^"]|\\")*)" for "(?P<field>(?:[^"]|\\")*)"$/
     */
    public function iFillInSelect2Field($field, $value)
    {
        $page = $this->getSession()->getPage();

        $this->openField($page, $field);
        $this->selectValue($page, $value);
    }

    /**
     * Fill Select2 input field and select a value
     *
     * @When /^(?:|I )fill in select2 input "(?P<field>(?:[^"]|\\")*)" with "(?P<value>(?:[^"]|\\")*)" and select "(?P<entry>(?:[^"]|\\")*)"$/
     */
    public function iFillInSelect2InputWithAndSelect($field, $value, $entry)
    {
        $page = $this->getSession()->getPage();

        $this->openField($page, $field);
        $this->fillSearchField($page, $value);
        $this->selectValue($page, $entry);
    }

    /**
     * Open Select2 choice list
     *
     * @param DocumentElement $page
     * @param string          $field
     * @throws \Exception
     */
    private function openField(DocumentElement $page, $field)
    {
        $fieldName = sprintf('#select2-%s-container', $field);

        $inputField = $page->find('css', $fieldName);
        if (!$inputField) {
            throw new \Exception('No field found');
        }

        $choice = $inputField->getParent()->find('css', '.select2-selection');
        if (!$choice) {
            throw new \Exception('No select2 choice found');
        }
        $choice->press();
    }

    /**
     * Fill Select2 search field
     *
     * @param DocumentElement $page
     * @param string          $value
     * @throws \Behat\Mink\Exception\ElementException
     * @throws \Exception
     */
    private function fillSearchField(DocumentElement $page, $value)
    {
        $select2Input = $page->find('css', '.select2-search__field');
        if (!$select2Input) {
            throw new \Exception('No input found');
        }
        $select2Input->setValue($value);
    }

    /**
     * Select value in choice list
     *
     * @param DocumentElement $page
     * @param string          $value
     * @throws \Exception
     */
    private function selectValue(DocumentElement $page, $value)
    {
        $chosenResults = $page->findAll('css', '.select2-results li');
        foreach ($chosenResults as $result) {
            if ($result->getText() == $value) {
                $result->click();
                return;
            }
        }

        throw new \Exception(sprintf('Value "%s" not found', $value));
    }
}
