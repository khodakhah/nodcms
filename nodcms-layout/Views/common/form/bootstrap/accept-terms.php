<div class="mt-checkbox-list">
    <label class="mt-checkbox mt-checkbox-outline <?php echo $class; ?>" for="<?php echo $field_id; ?>">
        <input value="1" type="checkbox" id="<?php echo $field_id; ?>" name="<?php echo $name; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?> <?php echo (isset($default)&&$default==1)?'checked':''; ?>>
        <?php echo str_replace(array("{company_name}","{terms_and_conditions}"),
            array(
                \Config\Services::settings()->get()['company'],
                '<a href="'.base_url(\Config\Services::language()->getLocale()."/terms-and-conditions").'" target="_blank">'._l("Terms & Conditions", $this).'</a>',
            ),_l("I agree to {company_name} {terms_and_conditions}.", $this)
        ); ?>
        <br><br>
        <?php echo str_replace(array("{company_name}","{privacy_policy}"),
            array(
                \Config\Services::settings()->get()['company'],
                '<a href="'.base_url(\Config\Services::language()->getLocale()."/privacy-policy").'" target="_blank">'._l("Privacy Policy", $this).'</a>',
            ),_l("{company_name} will use your personal data to help us to support any our services you might use. Please have a look at our {privacy_policy} for more information on how we use your data.", $this)
        ); ?>
        <span></span>
    </label>
</div>