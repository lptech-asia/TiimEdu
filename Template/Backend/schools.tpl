{% extends 'layout.tpl' %}
{% block header %}
    {{ LANG.t(REQUEST.vs(0) ~ '_index_header',"Tiimedu") }}
{% endblock %}
{% block body %}
<div class="col-md-12">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">{{ LANG.t('tiimedu_user_school_title',"Danh sách thành viên trường") }}</strong></h3>
            <div class="box-tools pull-right">
                <p class="text-muted">
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#customer-create"><i class="fa fa-plus"></i> Tạo Thành Viên</button>
                </p>
            </div>
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
                        <th>Trường</th>
                        <th>Trạng thái</th>
                        <th>{{ LANG.t('user_register_date',"Ngày đăng ký") }}</th>
                        <th>Chi tiết</th>
                    </tr>
                    {% for item in schools %}
                    <tr>
                        <td>{{ item.getId }}</td>
                        <td>
                            {{ item.getName ?? 'N/A' }}
                        </td>
                        <td>{{ item.getEmail }}</td>
                        <td>
                            {{ item.getPhone ?? 'N/A' }}
                        </td>
                        <td>{{ item.getAddress ?? 'N/A' }}</td>
                        <td>N/A</td>
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
<div class="modal fade" id="customer-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Tạo thành viên trường học</h4>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="{{ MODULE_URL }}createUserSchool">
                    <div class="form-group">
                        <label>Họ và Tên</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                    {# create select option to select university #}
                    <div class="form-group">
                        <label>University</label> <br>
                        <select class="form-control select2" name="university">
                            {# option default #}
                            <option value="">Chọn trường quản lý</option>
                            {% for university in universities %}
                                <option value="{{ university.id }}">{{ university.name }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary pull-right">Tạo tài khoản</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
{% endblock %}
{% block custom_js %}
{% endblock custom_js %}