{% extends 'layout.tpl' %}
{% block custom_css %}
{% endblock %}
{% block header %} Quản lý Phân loại Tài liệu {% endblock %}
{% block body %}
<div class="col-md-12">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Danh sách Loại Tài liệu</h3>
            <div class="box-tools pull-right">
                <p class="text-muted">
                    <hasperm>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#document-create"><i class="fa fa-plus"></i> Tạo Loại Tài Liệu</button>
                    </hasperm>
                </p>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <form action="#" method="post">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px;">#</th>
                                <th>Tiêu đề</th>
                                <th>Giới hạn</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th style="width: 200px;">Ngày tạo </th>
                                <th style="width: 200px;">Cập nhật </th>
                                <th style="width: 150px;">Sửa</th>
                                <th style="width: 150px;">Xoá</th>
                            </tr>
                        </thead>
                        <tbody id="order-data">
                        {% for type  in documentsTypes %}
                            <tr>
                                <td>{{ type.getId }}</td>
                                <td>{{ type.getName }}</td>
                                <td>{{ type.getLimit ?? 1 }}</td>
                                <td>{{ type.getDescription }}</td>
                                <td id="td-{{ type.getId }}">
                                    {% if type.getStatus %}
                                    <a href="javascript:setStatus({{ type.getId }},'documentStatus/0')" title="{% lang noedit global_btn_active = 'Trạng thái đang bật, bấm vào đây để TẮT' %}"><i class="fa fa-2x fa-toggle-on"></i></a>
                                    {% else %}
                                    <a href="javascript:setStatus({{ type.getId }},'documentStatus/1')" title="{% lang noedit global_btn_disable = 'Trạng thái đang tắt, bấm vào đây để BẬT' %}"><i class="fa fa-2x fa-toggle-off"></i></a>
                                    {% endif %}
                                </td>
                                <td>{{ type.getCreatedAt|date("d/m/Y - H:i A")}}</td>
                                <td>{{ type.getUpdatedAt|date("d/m/Y - H:i A")}}</td>
                                <td>
                                    <button type="button" class="btn btn-warning" title="Chỉnh sửa danh mục: #{{ category.getTitle }}" data-toggle="lightbox" data-remote="{{ BASE_URL }}hotels/categoryModalGenerator/{{ category.getId }}" data-title="Chỉnh sửa danh mục #{{ category.getTitle }}" data-width="1000">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </td>
                                {% hasperm REQUEST.vs(0)~'/deleteCategory' %}<td><hasperm><a title="{% lang noedit award_campaigns_btn_delete_hint = "Xoá ứng chiến dịch này" %}" data-objact="deleteCategory" data-objname="{{ category.getTitle }}" data-objid="{{ category.getId }}" data-toggle="modal" data-target="#modal-confirm" href="#"><i class="text-red fa-2x fa fa-close">&nbsp;</i></a></hasperm></td>{% endperm %}

                            </tr>
                            {% endfor %}
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

<div class="modal fade" id="document-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Tạo loại tài liệu</h4>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="{{ MODULE_URL }}documentCreate">
                    <div class="form-group">
                        <label>Tên Loại Tài Liệu</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="form-group">
                        <label>Giới hạn tải lên</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea rows="3" type="text" class="form-control" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary pull-right">Cập nhật thông tin</button>
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
    {% include 'partials/confirm.tpl' %}
    {% include 'partials/setStatus.tpl' %}
{% endblock custom_js %}
