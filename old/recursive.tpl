{* Recursive *}
<div class="form-group {$errors['ValueRecursive']['class']}">
    <label for="use_valueRecursive" class="col-sm-4 control-label">
        {__('Step 1', 'Recursive')}<br/>
    </label>
    <div class="col-sm-8">
        <div class="checkbox">
            <label>
                <input id="use_ValueRecursive" name="use_ValueRecursive" type="checkbox"
                       {if $use_ValueRecursive}checked{/if}>
                {__('Step 1', "Recursive poll")}
            </label>
        </div>
    </div>
</div>
