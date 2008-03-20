{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{default attribute_base=ContentObjectAttribute}
{let datetime=cond( is_set( $#collection_attributes[$attribute.id] ), $#collection_attributes[$attribute.id].content, $attribute.content )}
<div class="block">

<div class="date">
<div class="element">
<label>{'Year'|i18n( 'design/standard/content/datatype' )}:</label>
<input type="text" name="{$attribute_base}_datetime_year_{$attribute.id}" size="5" value="{section show=$datetime.is_valid}{$datetime.year}{/section}" />
</div>

<div class="element">
<label>{'Month'|i18n( 'design/standard/content/datatype' )}:</label>
<input type="text" name="{$attribute_base}_datetime_month_{$attribute.id}" size="3" value="{section show=$datetime.is_valid}{$datetime.month}{/section}" />
</div>

<div class="element">
<label>{'Day'|i18n( 'design/standard/content/datatype' )}:</label>
<input type="text" name="{$attribute_base}_datetime_day_{$attribute.id}" size="3" value="{section show=$datetime.is_valid}{$datetime.day}{/section}" />
&nbsp;
&nbsp;
&nbsp;
&nbsp;
</div>
</div>

<div class="time">
<div class="element">
<label>{'Hour'|i18n( 'design/standard/content/datatype' )}:</label>
<input type="text" name="{$attribute_base}_datetime_hour_{$attribute.id}" size="3" value="{section show=$datetime.is_valid}{$datetime.hour}{/section}" />
</div>

<div class="element">
<label>{'Minute'|i18n( 'design/standard/content/datatype' )}:</label>
<input type="text" name="{$attribute_base}_datetime_minute_{$attribute.id}" size="3" value="{section show=$datetime.is_valid}{$datetime.minute}{/section}" />
</div>

{if $attribute.contentclass_attribute.data_int2|eq(1)}
<div class="element">
<label>{'Second'|i18n( 'design/standard/content/datatype' )}:</label>
<input type="text" name="{$attribute_base}_datetime_second_{$attribute.id}" size="3" value="{section show=$datetime.is_valid}{$datetime.second}{/section}" />
</div>
{/if}

</div>

<div class="break"></div>

</div>
{/let}
{/default}
