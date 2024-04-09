<?php

class Api_Admin_FormbuilderTest extends BBDbApiTestCase
{
    protected $_initialSeedFile = 'mod_formbuilder.xml';

    /**
     *  Box_Exception.
     */
    public function testFormExceptions(): void
    {
        try {
            $this->api_admin->formbuilder_get_form(['id' => 10000]);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_create_form(['name' => null]);
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_create_form(['title' => 'Form title', 'style' => 'not existing style']);
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_add_field(['id' => 1, 'type' => 'type']);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_add_field(['id' => 1_000_000_000]);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_add_field([
                'id' => 1,
                'form_id' => 1,
                'type' => 'unexisting type',
            ]);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_get_form(['id' => 10000]);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_get_form_fields(['id' => 1]);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_get_field(['id' => 1_000_000]);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_delete_form(['id' => 1]);
            $this->api_admin->formbuilder_get_form(['id' => 1]);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_delete_field(['id' => 1]);
            $this->api_admin->formbuilder_get_field(['id' => 1]);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_delete_field(['id' => 1]);
            $this->api_admin->formbuilder_get_field(['id' => 1]);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_update_field(['id' => 3, 'description' => 'This is very awesome description.', 'name' => '']);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }

        try {
            $test = $this->api_admin->formbuilder_add_field([
                'description' => 'Form field description',
                'label' => 'Field label',
                'id' => 2,
                'form_id' => 2,
                'hide_label' => 0,
                'name' => 'Form field name',
                'type' => 'checkbox',
                'default_value' => 'Default',
                'required' => 0,
                'hidden' => 1,
                'readonly' => 0,
                'options' => ['Key' => '2', '2' => '2'],
                'show_initial' => 'initial',
                'show_middle' => 'middle',
                'show_prefix' => 'prefix',
                'show_suffix' => 'suffix',
                'text_size' => 499,
            ]);
            $this->api_admin->formbuilder_update_field($test);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_get_orders_count([]);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }

        try {
            $test = $this->api_admin->formbuilder_update_form_settings([
                'form_id' => 1,
                'form_name' => 'Second',
                'type' => 'non_existing type',
                'show_title' => 'Second',
            ]);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_copy_form([
                'form_id' => 2,
            ]);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_copy_form([
                'form_id' => 'non-existing-form',
                'name' => '',
            ]);
        } catch (Box_Exception) {
        }

        try {
            $this->api_admin->formbuilder_update_form_name(['form_id' => 2]);
            $this->fail('An expected exception has not been raised.');
        } catch (Box_Exception) {
        }
    }

    public function testFormCreate(): void
    {
        $id = $this->api_admin->formbuilder_create_form([
            'name' => 'New form',
        ]);
        $this->assertIsInt($id);
        $id2 = $this->api_admin->formbuilder_create_form([
            'type' => null,
            'name' => 'Form',
            'fields' => [
                'form_id' => $id,
                'type' => 'checkbox',
            ]]);
        $this->assertIsInt($id2);
    }

    public function testFieldAdd(): void
    {
        $fieldId = $this->api_admin->formbuilder_add_field(['form_id' => 1, 'type' => 'text']);
        $this->assertIsInt($fieldId);
        $test = $this->api_admin->formbuilder_add_field([
            'description' => 'Form field description',
            'label' => 'Field label',
            'form_id' => 2,
            'hide_label' => 0,
            'name' => 'Field name',
            'type' => 'checkbox',
            'default_value' => 'Default value',
            'required' => 0,
            'hidden' => 1,
            'readonly' => 0,
            'options' => ['0' => 'First json on create', '2' => 'Second json on create'],
            'show_initial' => 'initial',
            'show_middle' => 'middle',
            'show_prefix' => 'prefix',
            'show_suffix' => 'suffix',
            'text_size' => 499,
        ]);
        $this->assertIsInt($test);
        $this->api_admin->formbuilder_get_form([
            'id' => 1,
            'form_id' => 1,
            'type' => 'text',
            'options' => [
                '1' => '1',
            ],
        ]);
    }

    public function testFormGet(): void
    {
        $array = $this->api_admin->formbuilder_get_form(['id' => 1]);
        $this->assertIsArray($array);
        $this->assertTrue(isset($array['fields']));
        $this->assertNotEmpty($array['fields']);
    }

    public function testGetForm(): void
    {
        $form = $this->api_admin->formbuilder_get_form(['id' => 1]);
        $this->assertIsArray($form);
        $this->assertNotEmpty($form);
    }

    public function testGetFormFields(): void
    {
        $test = $this->api_admin->formbuilder_get_form_fields(['form_id' => 1]);
        $this->assertIsArray($test);
        $this->assertNotEmpty($test);
    }

    public function testGetField(): void
    {
        $test = $this->api_admin->formbuilder_get_field(['id' => 1]);
        $this->assertIsArray($test);
        $this->assertNotEmpty($test);
    }

    public function testGetForms(): void
    {
        $arr = $this->api_admin->formbuilder_get_forms();
        $test = $arr[0];
        $this->assertIsArray($test);
        $this->assertArrayHasKey('product_count', $test);
        $this->assertArrayHasKey('order_count', $test);
        $this->assertArrayHasKey('name', $test);
        $this->assertArrayHasKey('id', $test);
    }

    public function testDeleteForm(): void
    {
        $forms_before = count($this->api_admin->formbuilder_get_forms());
        $test = $this->api_admin->formbuilder_delete_form(['id' => 1]);
        $forms_after = count($this->api_admin->formbuilder_get_forms());
        $this->assertEquals($forms_before, $forms_after + 1);
        $this->assertTrue($test);
    }

    public function testDeleteField(): void
    {
        $fields_before = count($this->api_admin->formbuilder_get_form_fields(['form_id' => 1]));
        $test = $this->api_admin->formbuilder_delete_field(['id' => 1]);
        $fields_after = count($this->api_admin->formbuilder_get_form_fields(['form_id' => 1]));
        $this->assertEquals($fields_before, $fields_after + 1);
        $this->assertTrue($test);
    }

    public function testUpdateField(): void
    {
        $test = $this->api_admin->formbuilder_update_field(['id' => 3, 'description' => 'This is very awesome description.', 'name' => 'Form name']);
        $this->assertIsInt($test);
        $test = $this->api_admin->formbuilder_add_field([
            'description' => 'Form field description',
            'label' => 'Form field label',
            'id' => 2,
            'form_id' => 2,
            'hide_label' => 0,
            'name' => 'Form field name',
            'type' => 'checkbox',
            'default_value' => 'default',
            'required' => 0,
            'hidden' => 1,
            'readonly' => 0,
            'options' => ['Key' => '2', '2' => '3'],
            'show_initial' => 'initial',
            'show_middle' => 'middle',
            'show_prefix' => 'prefix',
            'show_suffix' => 'suffix',
            'text_size' => 499,
        ]);
        $test = $this->api_admin->formbuilder_update_field([
            'id' => 5,
            'form_id' => 1,
            'description' => 'This is field description',
            'hide_label' => 1,
            'name' => 'Form field name',
            'type' => 'text',
            'default_value' => 'default',
            'required' => 1,
            'hidden' => 0,
            'readonly' => 1,
            'options' => ['1' => 'First value', '2' => 'second one'],
            'show_initial' => 'aaaaaaa',
            'show_middle' => 'bbbbbbbbb',
            'show_prefix' => 'ccccccccc',
            'show_suffix' => 'ddddd',
            'text_size' => 500,
        ]);

        $this->assertIsInt($test);
    }

    public function testUpdateFormName(): void
    {
        $test = $this->api_admin->formbuilder_update_form_settings([
            'form_id' => 1,
            'form_name' => 'Second',
            'type' => 'horizontal',
            'show_title' => '0',
        ]);
        $this->assertIsBool($test);
        $this->assertTrue($test);
    }

    public function testCopyForm(): void
    {
        $test = $this->api_admin->formbuilder_add_field([
            'description' => 'This is awesome description. on create',
            'label' => 'Cre',
            'form_id' => 2,
            'hide_label' => 0,
            'name' => 'formnameon create',
            'type' => 'checkbox',
            'default_value' => 'i am defaulton create',
            'required' => 0,
            'hidden' => 1,
            'readonly' => 0,
            'options' => ['0' => 'First oneon create', '2' => 'second jsonon create'],
            'show_initial' => 'a',
            'show_middle' => 'middle',
            'show_prefix' => 'prefix',
            'show_suffix' => 'suffix',
            'text_size' => 499,
        ]);
        $test = $this->api_admin->formbuilder_copy_form([
            'form_id' => 1,
            'name' => 'Second',
        ]);
        $this->assertIsInt($test);
    }

    public function testGetPairs(): void
    {
        $test = $this->api_admin->formbuilder_get_pairs();
        $this->assertIsArray($test);
    }
}
