{% extends 'layout.tpl' %}
{% block custom_css %}
{% endblock %}
{% block header %}{% lang hotels_category_index_header = "Quản lý danh mục" %} {% endblock %}
{% block body %}
<div class="col-md-12">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">{% lang hotels_category_list_title = "Danh sách danh mục" %}</h3>
            <div class="box-tools pull-right">
            {% hasperm REQUEST.vs(0)~'/createCountries' %}
                <p class="text-muted">
                    <hasperm>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#countries-create"><i class="fa fa-plus"></i> Tạo Quốc gia</button>
                    </hasperm>
                </p>
            {% endperm %}
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <form action="#" method="post">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px;">#</th>
                                <th>Ảnh</th>
                                <th>Tiêu đề</th>
                                <th>Mô tả</th>
                                <th>Ngày tạo </th>
                                <th>Cập nhật </th>
                                <th style="width: 150px;">Sửa</th>
                                <th style="width: 150px;">Xoá</th>
                            </tr>
                        </thead>
                        <tbody id="order-data">
                        {% for country in countries %}
                            <tr>
                                <td>{{ country.getId }}</td>
                                <td>
                                    <a href="{{ country.getImage }}" data-toggle="lightbox" data-gallery="file_images_imgs" data-title="{{ country.getName }}" title="{% lang file_view_image = 'Xem hình đầy đủ' %}">
                                        <img style="width:100px" src="{{ country.getImage }}" alt="{{ country.getName }}" />
                                    </a>
                                </td>
                                <td>{{ country.getName }}</td>
                                <td>{{ country.getDescription }}</td>
                                <td>{{ country.getCreatedAt|date("d/m/Y - H:i A")}}</td>
                                <td>{{ country.getUpdatedAt|date("d/m/Y - H:i A")}}</td>
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

<div class="modal fade" id="countries-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Tạo chiến dịch</h4>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="{{ MODULE_URL }}createOrUpdate">
                    <input type="hidden" value="{{ category.getId }}" name="itemId">
                    <div class="form-group">
                        <label>Tên danh mục</label>
                        <input type="text" class="form-control" name="title" value="{{ category.getTitle }}">
                    </div>
                    <div class="form-group">
                        <label>Thẻ tiêu đề SEO</label>
                        <input type="text" class="form-control" name="meta_title" value="{{ category.getMetaTitle }}">
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <input type="text" class="form-control" name="descriptions" value="{{ category.getDescriptions }}">
                    </div>
                    <div class="form-group">
                        <label>Nội dung</label>
                        <textarea class="form-control" rows="5" name="content"> {{ category.getContent }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class="radio">
                            <label><input type="radio" name="status" value="1" {{ category.getStatus == 1 ? 'checked' }}> Hiển thị</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="status" value="0" {{ category.getStatus == 0 ? 'checked' }}> Ẩn</label>
                        </div>
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
