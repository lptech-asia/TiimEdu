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
            <h3 class="box-title">Danh sách chương trình học</strong></h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>sku</th>
                        <th>program_name</th>
                        <th>degree</th>
                        <th>duration</th>
                        <th>program_fee</th>
                        <th>language_required</th>
                        <th>gpa_required</th>
                        <th>finance_proof</th>
                        <th>acceptance_rate</th>
                        <th>international_students</th>
                        <th>updated_by</th>
                        <th>created_at</th>
                        <th>updated_at</th>
                        <th>View</th>
                    </tr>
                    {% if programs %}
                        {% for item in programs %}
                            <tr>
                                <td>{{ item.getId }}</td>
                                <td>{{ item.getProgramId }}</td>
                                <td>{{ item.getProgramName }}</td>
                                <td>{{ item.getDegree }}</td>
                                <td>{{ item.getDuration }}</td>
                                <td>{{ item.getWholeProgramFee }}</td>
                                <td>{{ item.getLanguageRequired }}</td>
                                <td>{{ item.getGpaRequired }}</td>
                                <td>{{ item.getFinanceProof }}</td>
                                <td>{{ item.getAcceptanceRate }}</td>
                                <td>{{ item.getInternationalStudents }}</td>
                                <td>{{ item.getUpdatedBy }}</td>
                                <td>{{ item.getCreatedAt|date('d/m/Y H:i:s') }}</td>
                                <td>{{ item.getUpdatedAt|date('d/m/Y H:i:s') }}</td>
                                <td>
                                    <a class="btn btn-default" href="{{ MODULE_URL }}programView" data-toggle="lightbox" data-gallery="file_images_imgs" data-title="{{ item.getName }}" title="Chi tiết">
                                        <i class="fa fa-eye"></i> Xem
                                    </a>
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
