{% for university in universities %}
<tr>
    <td>{{ university.getId }}</td>
    <td>{{ university.getSku }}</td>
    <td>{{ university.getName }}</td>
    <td>{{ university.getCountryId }}</td>
    <td>{{ university.getCity }}</td>
    <td>{{ university.getAddress }}</td>
    <td>{{ university.getFoundYear }}</td>
    <td>{{ university.getType }}</td>
    <td>{{ university.getCreatedAt|date("d/m/Y - H:i A") }}</td>
    <td>{{ university.getUpdatedAt|date("d/m/Y - H:i A") }}</td>
    <td>
        <a href="{{ MODULE_URL }}university/{{ university.getId }}">
            <i class="fa fa-2x fa-eye">&nbsp;</i>
        </a>
    </td>
    <td>
        <a data-objact="deleteUniversity" data-objname="{{ university.getName }}" data-objid="{{ university.getId }}" data-toggle="modal" data-target="#modal-confirm" href="#">
            <i class="text-red fa-2x fa fa-close">&nbsp;</i>
        </a>
    </td>
</tr>
{% endfor %}
