{% extends 'layout.tpl' %}
{% block custom_css %}
{% endblock %}
{% block header %}Chương trình học {% endblock %}
{% block body %}
<div class="col-md-12">
    {% include 'Backend/Universities/counter.tpl' %}
</div>
<div class="col-md-12">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Danh sách ứng viên</strong></h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Ứng viên</th>
                        <th>Chương trình học</th>
                        <th>Học bổng</th>
                        <th>Hỗ trợ</th>
                        <th>Trạng thái Duyệt</th>
                        <th>{{ LANG.t('user_register_date',"Ngày đăng ký") }}</th>
                        <th>Chi tiết</th>
                    </tr>
                    {% if applications %}
                        {% for item in applications %}
                            {% set user = item.getUser %}
                            {% set program = item.getProgram %}
                            {% set scholarship = item.getScholarship %}
                            {% set supportCount = item.countConversations %}
                            <tr>
                                <td>{{ item.getId }}</td>
                                <td> {{ user.getName }}</td>
                                <td>{{ program.getProgramName }}</td>
                                <td>
                                    <strong>{{ scholarship.getName }}</strong><br>
                                    <span>{{ scholarship.getDescription }}</span>
                                </td>
                                <td>{{ supportCount ?? 0 }}</td>
                                <td>
                                    <span class="label label-{{ item.getStatus == 1 ? 'primary' : (item.getStatus == 2 ? 'success' : (item.getStatus == 3 ? 'danger' : 'default')) }}">
                                    {{ item.getStatus == 1 ? 'Đã xem' : (item.getStatus == 2 ? 'Đã duyệt' : (item.getStatus == 3 ? 'Từ chối' : 'Chưa xem')) }}
                                    </span>
                                </td>
                                <td>{{ item.getCreatedAt|date('d/m/Y h:i') }}</td>
                                <td>
                                    <a href="{{ MODULE_URL }}application/{{ item.getId }}" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                        {% endfor %}
                    {% else %}
                        <tr>
                            <td colspan="10" class="text-center">Không có dữ liệu</td>
                        </tr>
                    {% endif %}
                </table>
            </div><!-- .table-responsive -->
        </div><!-- .box-body -->
        <div class="box-footer">
            {% include 'partials/paging.tpl' %}
        </div><!-- .box-footer -->
    </div>
</div>
{% endblock %}
{% block custom_js %}
    {% include 'partials/rte.tpl' %}
{% endblock custom_js %}
