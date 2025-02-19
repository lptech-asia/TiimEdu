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
    <td id="td-{{ university.getId }}">
        {% if university.getStatus %}
            <a href="javascript:setStatus({{ university.getId }},'disable')" title="{% lang noedit global_btn_active = 'Trạng thái đang bật, bấm vào đây để TẮT' %}"><i class="fa fa-2x fa-toggle-on"></i></a>
        {% else %}
            <a href="javascript:setStatus({{ university.getId }},'enable')" title="{% lang noedit global_btn_disable = 'Trạng thái đang tắt, bấm vào đây để BẬT' %}"><i class="fa fa-2x fa-toggle-off"></i></a>
        {% endif %}
    </td>
    <td>
        <a href="{{ MODULE_URL }}university/{{ university.getId }}">
            <i class="fa fa-2x fa-eye">&nbsp;</i>
        </a>
    </td>
    <td>
        <a data-objact="delete" data-objname="{{ university.getTitle }}" data-objid="{{ university.getId }}" data-toggle="modal" data-target="#modal-confirm" href="#">
            <i class="text-red fa-2x fa fa-close">&nbsp;</i>
        </a>
    </td>
</tr>
{% endfor %}
