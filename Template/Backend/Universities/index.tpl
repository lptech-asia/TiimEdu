{% extends 'layout.tpl' %}
{% block custom_css %}
{% endblock %}
{% block header %}Quản lý Trường Đại Học{% endblock %}
{% block body %}
<div class="col-md-12">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Danh sách Trường Đại Học</h3>
            <div class="box-tools pull-right">
                <button data-toggle="modal" data-target="#import-universities" class="btn btn-primary"><i class="fa fa-upload"></i> Import University</button>
                <button data-toggle="modal" data-target="#import-programs" class="btn btn-warning"><i class="fa fa-upload"></i> Import Program</button>
            </div>
        </div>
        {# <div class="box-header with-border">
            <div class="row">{% include 'Backend/partials/filter.tpl' %}</div>
        </div> #}
        <div class="box-body">
            <div class="table-responsive">
                <form action="#" method="post">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ LANG.t(REQUEST.vs(0) ~ '_universities_sku', 'SKU') }}</th>
                                <th>{{ LANG.t(REQUEST.vs(0) ~ '_universities_title', 'Title') }}</th>
                                {# Country #}
                                <th>{{ LANG.t(REQUEST.vs(0) ~ '_universities_country', 'Country') }}</th>
                                {# City #}
                                <th>{{ LANG.t(REQUEST.vs(0) ~ '_universities_city', 'City') }}</th>
                                {# Address #}
                                <th>{{ LANG.t(REQUEST.vs(0) ~ '_universities_address', 'Address') }}</th>
                                {# Found Year #}
                                <th>{{ LANG.t(REQUEST.vs(0) ~ '_universities_found_year', 'Found Year') }}</th>
                                {# Type #}
                                <th>{{ LANG.t(REQUEST.vs(0) ~ '_universities_type', 'Type') }}</th>
                                <th>{{ LANG.t(REQUEST.vs(0) ~ '_universities_author', "Created By") }}</th>
                                <th>{{ LANG.t(REQUEST.vs(0) ~ '_updated_at', "Updated") }}</th>
                                <th style="width: 40px">{{ LANG.t(REQUEST.vs(0) ~ '_detail', 'Chi tiết') }}</th>
                                <th style="width: 40px">{{ LANG.t(REQUEST.vs(0) ~ '_delete', 'Xoá') }}</th>
                            </tr>
                        </thead>
                        <tbody id="order-data">
                            {% include 'Backend/Universities/list.tpl' %}
                        </tbody>
                    </table>
                </form>
            </div><!-- .table-responsive -->
        </div><!-- .box-body -->
        <div class="box-footer">
            {% include 'partials/paging.tpl' %}
        </div><!-- .box-footer -->
    </div>
</div>
<div class="modal fade" id="import-universities">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Nhập liệu Trường Đại Học</h4>
            </div>
            <form role="form" action="{{ MODULE_URL }}importUniversities" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file_import">Chọn tệp trường đại học</label>
                        <input type="file" name="import_file" id="file_import" required>
                        <p class="help-block">Vui lòng chọn file excel đã xuất ra từ mẫu, nếu sai cấu trúc sẽ không thể sử dụng.</p>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Nhập dữ liệu</button>
                </div>
            </form>
        </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="import-programs">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Nhập liệu Chương Trình Học</h4>
            </div>
            <form role="form" action="{{ MODULE_URL }}importPrograms" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="{{ university.getId }}">
                <div class="modal-body">
                    {% if hotel is not empty %}
                        <div class="form-group">
                            <label>Tên Trường Đang nhập liệu</label>
                            <input readonly class="form-control" value="{{ university.getTitle }}">
                        </div>
                    {% endif %}
                    <div class="form-group">
                        <label for="file_import">Chọn tệp Chương Trình Học</label>
                        <input type="file" name="import_file" id="file_import" required>
                        <p class="help-block">Vui lòng chọn file excel đã xuất ra từ mẫu, nếu sai cấu trúc sẽ không thể sử dụng.</p>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Nhập dữ liệu</button>
                </div>
            </form>
        </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="import-prices">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Nhập liệu bảng giá</h4>
            </div>
            <form role="form" action="{{ MODULE_URL }}importPrices" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    {% if hotel is not empty %}
                        <div class="form-group">
                            <label>Tên chiến dịch</label>
                            <input readonly class="form-control" value="{{ hotel.getTitle }}">
                        </div>
                    {% endif %}
                    <div class="form-group">
                        <label for="file_import">Chọn tệp bảng giá</label>
                        <input type="file" name="import_file" id="file_import" required>
                        <p class="help-block">Vui lòng chọn file excel đã xuất ra từ mẫu, nếu sai cấu trúc sẽ không thể sử dụng.</p>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Nhập dữ liệu</button>
                </div>
            </form>
        </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="import-review">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Nhập liệu đánh giá tổng</h4>
            </div>
            <form role="form" action="{{ MODULE_URL }}importReview" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file_import">Chọn tệp đánh giá</label>
                        <input type="file" name="import_file" id="file_import" required>
                        <p class="help-block">Vui lòng chọn file excel đã xuất ra từ mẫu, nếu sai cấu trúc sẽ không thể sử dụng.</p>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Nhập dữ liệu</button>
                </div>
            </form>
        </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
{% endblock %}
{% block custom_js %}
    {% include 'partials/confirm.tpl' %}
    {% include 'partials/setStatus.tpl' %}
{% endblock custom_js %}