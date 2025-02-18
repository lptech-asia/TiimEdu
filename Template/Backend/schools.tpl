{% extends 'layout.tpl' %}
{% block header %}
    {{ LANG.t(REQUEST.vs(0) ~ '_index_header',"Tiimedu") }}
{% endblock %}
{% block body %}
<div class="col-md-12">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">{{ LANG.t('hotels_user_title',"Danh sách thành viên") }}</strong></h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>{{ LANG.t('user_firstname',"Tên") }}</th>
                        <th>{{ LANG.t('user_email',"Email") }}</th>
                        <th>{{ LANG.t('user_phone',"Số điện thoại") }}</th>
                        <th>Địa chỉ</th>
                        <th>Trạng thái</th>
                        <th>{{ LANG.t('user_register_date',"Ngày đăng ký") }}</th>
                        <th>Chi tiết</th>
                    </tr>
                    {% for item in schools %}
                    <tr>
                        <td>{{ item.getId }}</td>
                        <td>
                            {{ item.getName }}
                            <span class="label label-{{ item.agency and item.agency.getStatus == 1 ? 'success' : item.agency.getStatus == 0 ? 'warning' }}">{{ item.agency ? 'B2B' }}</span>
                        </td>
                        <td>{{ item.getEmail }}</td>
                        <td>
                            {{ item.getPhone }}
                        </td>
                        <td>{{ item.getAddress }}</td>
                        <td>
                            <span class="label label-{{ item.getStatus ? 'success' : 'danger' }}">{{ item.getStatus ? 'Hoạt động' : 'Chưa kích hoạt' }}</span>
                        </td>
                        <td>{{ item.getRegisterDate }}</td>
                        <td>
                            <a href="{{ MODULE_URL }}student/{{ item.getId }}" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                    {% endfor %}
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
{% endblock custom_js %}