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
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Thông tin hồ sơ</a></li>
            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Tài liệu</a></li>
            <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="true">Hồ sơ ứng tuyển</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="row">
                    <form class="col-md-8" action="{{ MODULE_URL }}" method="post" disabled>
                        <div class="box">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><i class="fa fa-clock-o margin-r-5"></i> Họ và Tên</label>
                                            <input type="text" class="form-control" placeholder="" value="{{ student.getIdentifyName }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><i class="fa fa-clock-o margin-r-5"></i> Năm sinh</label>
                                            <input type="text" class="form-control" placeholder="" value="{{ student.getDateOfBirth }}">
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
                                            <label><i class="fa fa-clock-o margin-r-5"></i> Số CCCD</label>
                                            <input type="text" class="form-control" placeholder="" value="{{ student.getIdentifyNumber }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><i class="fa fa-clock-o margin-r-5"></i> Địa chỉ</label>
                                            <input type="text" class="form-control" placeholder="" value="{{ student.getIdentifyAddress }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><i class="fa fa-clock-o margin-r-5"></i> Số Passport</label>
                                            <input type="text" class="form-control" placeholder="" value="{{ student.getPassportNumber }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><i class="fa fa-clock-o margin-r-5"></i> Ngày phát hành</label>
                                            <input type="text" class="form-control" placeholder="" value="{{ student.getPassportIssueAt }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><i class="fa fa-clock-o margin-r-5"></i> Ngày hết hạn</label>
                                            <input type="text" class="form-control" placeholder="" value="{{ student.getPassportExpiredAt }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><i class="fa fa-clock-o margin-r-5"></i> Quốc tịch</label>
                                            <input type="text" class="form-control" placeholder="" value="{{ student.getPassportNationality }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box">
                            <div class="box-body">
                                <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><i class="fa fa-clock-o margin-r-5"></i> Trường Đại Học</label>
                                        <input type="text" class="form-control" placeholder="" value="{{ student.getUniversityName }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><i class="fa fa-clock-o margin-r-5"></i> Ngành Học</label>
                                        <input type="text" class="form-control" placeholder="" value="{{ student.getMajorName }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><i class="fa fa-clock-o margin-r-5"></i> Loại Chương Trình</label>
                                        <input type="text" class="form-control" placeholder="" value="{{ student.getDegree }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><i class="fa fa-clock-o margin-r-5"></i> Điểm Gpa</label>
                                        <input type="text" class="form-control" placeholder="" value="{{ student.getGpa }}">
                                    </div>
                                </div>
                                </div>
                            </div>
                            {# <div class="box-footer">
                                <button class="btn btn-default">Trở về</button>
                                <button type="submit" class="btn btn-primary pull-right">Cập nhật</button>
                            </div> #}
                        </div>

                        
                    </form>

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
                                    <li class="list-group-item">
                                        <b>Address</b> <a class="pull-right">{{ user.getAddress }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">Thông tin định danh</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="box-group" id="accordion">
                                    <div class="panel box box-primary">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                    Thông tin CCCD
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse">
                                            <div class="box-body">
                                            <div>
                                                    <strong><i class="fa fa-book margin-r-5"></i> CCCD Mặt Trước</strong>
                                                    <br>
                                                    <img class="img-responsive pad" src="{{ student.getIdentifyFrontImage }}">
                                                </div>
                                                <div>
                                                    <strong><i class="fa fa-book margin-r-5"></i> CCCD Mặt Sau</strong>
                                                    <br>
                                                    <img class="img-responsive pad" src="{{ student.getIdentifyBackImage }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel box box-danger">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                                Thông tin Passport
                                            </a>
                                            </h4>
                                        </div>
                                        <div id="collapseTwo" class="panel-collapse collapse">
                                            <div class="box-body">
                                                <img class="img-responsive pad" src="{{ student.getPassportImage }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Danh sách tài liệu</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool" type="button">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>#</th>
                                    <th>Tên</th>
                                    <th>File</th>
                                    <th>Loại</th>
                                    <th>Ngày cập nhật</th>
                                </tr>
                                {% for document in documents %}
                                <tr>
                                    <td>{{ document.getId }}</td>
                                    <td>{{ document.getName }}</td>
                                    <td>{{ document.getFile }}</td>
                                    <td>{{ document.getType.getName }}</td>
                                    <td>{{ document.getCreatedAt|date('d/m/Y h:i:s') }}</td>
                                </tr>
                                {% endfor %}
                            </table>
                        </div><!-- .table-responsive -->
                    </div>
                    <div class="box-footer">
                        {% include 'partials/paging.tpl' %}
                    </div><!-- .box-footer -->
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_3">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Hồ sơ ứng tuyển</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool" type="button">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>#</th>
                                    <th>Tên Hồ Sơ</th>
                                    <th>Trường ứng tuyển</th>
                                    <th>Chương trình</th>
                                    <th>Cover Letter</th>
                                    <th>Cover Attachment</th>
                                    <th>Scholarship Essay</th>
                                    <th>Essay Attachment</th>
                                    <th>Ngày nộp</th>
                                    <th>Trạng thái</th>
                                    <th>Người cập nhật cuối</th>
                                </tr>
                                {% for application in applications %}
                                <tr>
                                    <td>{{ application.getId }}</td>
                                    <td>
                                        {{ application.getName }}
                                    </td>
                                    <td>{{ application.getSchool.getName }}</td>
                                    <td>{{ application.getProgram.getProgramName }}</td>
                                    <td>
                                        <span class="label label-{{ application.getCoverLetter ? 'success' : 'danger' }}">
                                            {{ application.getCoverLetter ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="label label-{{ application.getCoverLetterAttachment ? 'success' : 'danger' }}">
                                            {{ application.getCoverLetterAttachment ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="label label-{{ application.getScholarshipEssay ? 'success' : 'danger' }}">
                                            {{ application.getScholarshipEssay ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="label label-{{ application.getScholarshipEssayAttachment ? 'success' : 'danger' }}">
                                            {{ application.getScholarshipEssayAttachment ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>{{ application.getCreatedAt }}</td>
                                    <td>
                                        {{ application.getStatus == 0 ? 'Chưa xem' : application.getStatus == 1 ? 'Đã xem' : application.getStatus == 2 ? 'Đã duyệt' : application.getStatus == 3 ? 'Đã từ chối' : '' }}
                                    </td>
                                    <td>{{ application.getUpdatedBy ?? 'N/A' }}</td>
                                </tr>
                                {% endfor %}
                            </table>
                        </div><!-- .table-responsive -->
                    </div>
                    <div class="box-footer">
                        {% include 'partials/paging.tpl' %}
                    </div><!-- .box-footer -->
                </div>
            </div>
            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div>
</section>
{% endblock %}
{% block custom_js %}
{% endblock custom_js %}