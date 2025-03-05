{% extends 'layout.tpl' %}
{% block header %}
    {{ LANG.t(REQUEST.vs(0) ~ '_index_header',"Tiimedu") }}
{% endblock %}
{% block custom_css %}
<style>
.mt-10 { margin-top:10px;border-bottom: 1px solid #7777772b;padding-bottom: 13px;}
hr {margin-top:unset;}
</style>    
{% endblock custom_css %}
{% block body %}
<section class="content">
<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-header width-border">
                <h3 class="box-title">Thông tin cá nhân</h3>
            </div>
            <div class="box-body">
                <form action="{{ MODULE_URL }}" method="post">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><i class="fa fa-clock-o margin-r-5"></i> Họ và Tên</label>
                                <input type="text" class="form-control" placeholder="" value="{{ user.getName }}">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><i class="fa fa-clock-o margin-r-5"></i> Năm sinh</label>
                                <input type="text" class="form-control" placeholder="" value="{{ user.getBirthday }}">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><i class="fa fa-clock-o margin-r-5"></i> Giới tính</label>
                                <select class="form-control select2">
                                    <option value="1" {{ student.getGender == 1 ? 'selected' : '' }}>Nam</option>
                                    <option value="2" {{ student.getGender == 2 ? 'selected' : '' }}>Nữ</option>
                                    <option value="3" {{ student.getGender == 3 ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><i class="fa fa-clock-o margin-r-5"></i> Phone</label>
                                <input type="text" class="form-control" placeholder="" value="{{ user.getPhone }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><i class="fa fa-clock-o margin-r-5"></i> Địa chỉ</label>
                                <input type="text" class="form-control" placeholder="" value="{{ user.getAddress }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="send_email"> Bạn có chắc sẽ thay Đổi Thông tin cá nhân người dùng này?
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary pull-right">Cập nhật</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header width-border">
                <h3 class="box-title">Đổi mật khẩu</h3>
            </div>
            <div class="box-body">
                <form action="{{ MODULE_URL }}" method="post">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><i class="fa fa-clock-o margin-r-5"></i> Mật khẩu mới</label>
                                <input type="text" class="form-control" placeholder="Mật khẩu mới ...">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><i class="fa fa-clock-o margin-r-5"></i>Nhập lại Mật khẩu mới</label>
                                <input type="text" class="form-control" placeholder="Nhập lại Mật khẩu mới như trên vừa nhập ...">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="send_email"> Gửi mật khẩu mới đến email
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-danger pull-right">Đổi mật khẩu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="box box-warning">
            <div class="box-header width-border">
                <h3 class="box-title">Trường đại học thuộc tài khoản này quản lý</h3>
                <div class="box-tools pull-right">
                    <a href="{{ MODULE_URL }}university/{{ school.getId }}" class="btn btn-warning btn-sm">
                        <i class="fa fa-arrow-right"></i> Xem chi tiết
                    </a>
                </div>
            </div>
            <div class="box-body">
                {% if school %}
                    <strong><i class="fa fa-book margin-r-5"></i> Tên Trường</strong>
                    <p class="text-muted mt-10"> {{ school.getName }} </p>
                    <div class="row">
                        <div class="col-sm-6">
                            <strong><i class="fa fa-code margin-r-5"></i> Năm Thành Lập</strong>
                            <p class="text-muted mt-10">
                                {{ school.getFoundYear }}
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <strong><i class="fa fa-pencil margin-r-5"></i> Mã Trường</strong>
                            <p class="text-muted mt-10">
                                {{ school.getSku }}
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <strong><i class="fa fa-location-arrow margin-r-5"></i> Loại tổ chức</strong>
                            <p class="text-muted mt-10">
                               {{ school.getType }}
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <strong><i class="fa fa-location-arrow margin-r-5"></i> Địa chỉ</strong>
                            <p class="text-muted mt-10">
                               {{ school.getAddress }}
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <strong><i class="fa fa-location-arrow margin-r-5"></i> Thành phố</strong>
                            <p class="text-muted mt-10">
                               {{ school.getCity }}
                            </p>
                        </div>
                        {% if school.getDescription %}
                        <div class="col-sm-6">
                            <strong><i class="fa fa-language margin-r-5"></i> Mô tả</strong>
                            <p class="text-muted mt-10">
                                {{ school.getDescription ?? 'N/A'}}
                            </p>
                        </div>
                        {% endif %}
                        {# website #}
                        <div class="col-sm-6">
                            <strong><i class="fa fa-language margin-r-5"></i> Website</strong>
                            <p class="text-muted mt-10">
                                {{ school.getWebsite ?? 'N/A'}}
                            </p>
                        </div>
                        {# email #}
                        <div class="col-sm-6">
                            <strong><i class="fa fa-language margin-r-5"></i> Email </strong>
                            <p class="text-muted mt-10">
                                {{ school.getEmail ?? 'N/A'}}
                            </p>
                        </div>
                        {# phone #}
                        <div class="col-sm-6">
                            <strong><i class="fa fa-language margin-r-5"></i> Phone</strong>
                            <p class="text-muted mt-10">
                                {{ school.getPhone ?? 'N/A'}}
                            </p>
                        </div>
                        {# campus_residence #}
                        <div class="col-sm-6">
                            <strong><i class="fa fa-language margin-r-5"></i> Campus Residence </strong>
                            <p class="text-muted mt-10">
                                {{ school.getCampusResidence ?? 'N/A' }}
                            </p>
                        </div>
                        {# enrollment_email #}
                        <div class="col-sm-6">
                            <strong><i class="fa fa-clock-o margin-r-5"></i>Enrollment Email </strong>
                            <p class="text-muted mt-10">
                                {% for email in school.getEnrollmentEmail|split(',') %}
                                    <span>{{ email }}</span><br>
                                {% endfor %}
                            </p>
                        </div>
                        {# accountant_email #}
                        <div class="col-sm-6">
                            <strong><i class="fa fa-clock-o margin-r-5"></i>Accountant Email </strong>
                            <p class="text-muted mt-10">
                                {% for email in school.getAccountantEmail|split(',') %}
                                    <span>{{ email }}</span><br>
                                {% endfor %}
                            </p>
                        </div>
                        {# management_email #}
                        <div class="col-sm-6">
                            <strong><i class="fa fa-clock-o margin-r-5"></i>Management Email </strong>
                            <p class="text-muted mt-10">
                                {% for email in school.getManagementEmail|split(',') %}
                                    <span>{{ email }}</span><br>
                                {% endfor %}
                            </p>
                        </div>

                        {# created_at #}
                        <div class="col-sm-6">
                            <strong><i class="fa fa-clock-o margin-r-5"></i>Created At </strong>
                            <p class="text-muted mt-10">
                                <span>{{ school.getCreatedAt }}</span>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <strong><i class="fa fa-clock-o margin-r-5"></i>Updated At </strong>
                            <p class="text-muted mt-10">
                                <span>{{ school.getUpdatedAt }}</span>
                            </p>
                        </div>
                    </div>
                {% else %}
                    <div class="alert alert-danger">
                        <h4><i class="icon fa fa-ban"></i> Thông báo!</h4>
                        Hiện tại tài khoản này chưa quản lý bất kỳ 1 Trường Học nào, vui lòng kiểm tra lại hoặc cập nhật Trường Học vào tài khoản cho họ!
                    </div>
                {% endif %}

                <div class="box box-{{ school ? 'danger' : 'primary' }}">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ school ? 'Thay đổi Trường Học của tài khoản' : 'Thêm Trường Học vào tài khoản' }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form role="form" action="{{ MODULE_URL }}addSchoolToUser" method="post">
                            <input type="hidden" value="{{ user.getId }}" name="user_id">
                        <!-- text input -->
                            <div class="form-group">
                                <label>Tên Trường/SKU</label>
                                <select name="school_id" class="select2 form-control live-search-school" id="live-search-school"> 
                                    {% if school %}
                                        <option disabled selected>{{ school.getName }}</option>
                                    {% else %}
                                        <option disabled selected>Chọn Trường Học</option>
                                    {% endif %}
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary pull-right">Cập nhật ngay</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle" src="{{ user.getAvatar is not empty ?  user.getAvatar : ROOT_URL ~'themes/vendor/AdminLTE/dist/img/avatar.png' }}" alt="User profile picture">
                <h3 class="profile-username text-center"> {{ user.getName }}</h3>
                <p class="text-muted text-center">{{ user.getCreatedAt }}</p>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Email</b> <a class="pull-right">{{ user.getEmail }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Phone</b> <a class="pull-right">{{ user.getPhone }}</a>
                    </li>
                </ul>
                {% if user.getStatus %}
                    <button type="button" class="btn btn-block btn-danger btn-lg">Khoá tài khoản này</button>
                {% else %}
                    <button type="button" class="btn btn-block btn-primary btn-lg">Kích hoạt tài khoản</button>
                {% endif %}
            </div>
        </div>
    </div>
</div>
</section>
{% endblock %}
{% block custom_js %}
<script>
    $(document).ready(function() {
        var $eventSelect = $("#live-search-school");
        $eventSelect.select2({
            minimumInputLength: 3,
            ajax: {
                url: '{{ MODULE_URL }}liveSearchUniversity',
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.name + ' - ' +item.sku,
                                id: item.id
                            };
                        })
                    };
                }
            }
        });
    });
</script>
{% endblock custom_js %}