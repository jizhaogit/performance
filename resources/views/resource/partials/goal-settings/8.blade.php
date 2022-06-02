<div class="table-responsive">
    <table cellspacing="0" cellpadding="0" class="table table-condensed table-bordered">
        <tbody>
            <tr>
                <td>
                    <p><strong>Tag</strong></p>
                </td>
                <td>
                    <p><strong>When to use</strong></p>
                </td>
            </tr>
            @php
            foreach($tags as $tag){ 
            @endphp
            <tr>
                <td>
                    <p>{{$tag['name']}}</p>
                </td>
                <td>
                    <p>{{$tag['description']}}</p>
                </td>
            </tr>
            @php
            }
            @endphp
        </tbody>
    </table>
</div>