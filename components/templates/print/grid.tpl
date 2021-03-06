<table class="wide text-center">

    <tr>
        {foreach item=Column from=$Columns}
            <td><b>{$Column->GetCaption()}</b></td>
        {/foreach}
    </tr>

    {foreach item=Row from=$Rows name=RowsGrid}
        <tr>
            {foreach item=RowColumn from=$Row}
                <td>
                    {$RowColumn}
                </td>
            {/foreach}
        </tr>
    {/foreach}

    {if $Grid->HasTotals()}
        <tr>
            {foreach item=Total from=$Totals}
                {strip}
                    <td>
                    {if not $Total.IsEmpty}
                        {if $Total.CustomValue}
                            {$Total.UserHTML}
                        {else}
                            {$Total.Aggregate} = {$Total.Value}
                        {/if}
                    {/if}
                    </td>
                {/strip}
            {/foreach}
        </tr>
    {/if}

</table>
