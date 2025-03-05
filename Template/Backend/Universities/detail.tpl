{% extends 'layout.tpl' %}
{% block custom_css %}
{% endblock %}
{% block header %}{% lang tiimedu_university_detail_header = "Chi tiết trường học" %} {% endblock %}
{% block body %}
<div class="col-md-12">
    {% include 'Backend/Universities/counter.tpl' %}
</div>
<div class="col-md-12">
    <form class="box box-info" method="post" action="{{ MODULE_URL }}university">
        <input type="hidden" name="id" value="{{ university.getId }}">
        <div class="box-header with-border">
            <h3 class="box-title">Quản lý thông tin trường</h3>
            <div class="box-tools pull-right">
                <button type="submit" class="btn btn-primary pull-right">Save changes</button>
            </div>
        </div>
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tên trường</label>
                    <input type="text" class="form-control" name="name" value="{{ university.getName }}">
                </div>

                <div class="form-group">
                    <label>Loại</label>
                    <input type="text" class="form-control" name="type" value="{{ university.getType }}">
                </div>
                
                <div class="form-group">
                    <label>SKU</label>
                    <input type="text" class="form-control" name="sku" value="{{ university.getSku }}">
                </div>
                
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" class="form-control" name="phone" value="{{ university.getPhone }}">
                </div>
                
                <div class="form-group">
                    <label>Website</label>
                    <input type="text" class="form-control" name="website" value="{{ university.getWebsite }}">
                </div>

                <div class="form-group">
                    <label>Logo</label>
                    <input type="text" class="form-control" name="logo" value="{{ university.getLogo }}">
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="text" class="form-control" name="address" value="{{ university.getAddress }}">
                </div>
                
                <div class="form-group">
                    <label>Quốc gia</label>
                    <input type="text" class="form-control" name="country_id" value="{{ university.getCountry.getName }}" disabled>
                </div>

                <div class="form-group">
                    <label>Thành phố</label>
                    <input type="text" class="form-control" name="city" value="{{ university.getCity }}">
                </div>

                <div class="form-group">
                    <label>Folder image</label>
                    <input type="text" class="form-control" name="image_folder" value="{{ university.getImageFolder }}">
                </div>

                <div class="form-group">
                    <label>Năm Thành Lập</label>
                    <input type="text" class="form-control" name="found_year" value="{{ university.getFoundYear }}">
                </div>

                <div class="form-group">
                    <label>Brochure</label>
                    <input type="text" class="form-control" name="brochure" value="{{ university.getBrochure }}">
                </div>

                <div class="form-group">
                    <label>Campus residence</label>
                    <textarea type="text" class="form-control" name="campus_residence">{{ university.getCampusResidence }}</textarea>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>lastest qs ranking</label>
                            <input type="text" class="form-control" name="lastest_qs_ranking" value="{{ university.getLastestQsRanking }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>lastest shanghai ranking</label>
                            <input type="text" class="form-control" name="lastest_shanghai_ranking" value="{{ university.getLastestShanghaiRanking }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>lastest nationa ranking</label>
                            <input type="text" class="form-control" name="lastest_national_ranking" value="{{ university.getLastestNationaRanking }}">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Full description</label>
                    <textarea class="form-control LP-rte" id="content" rows="10" name="description">{{ university.getDescription }}</textarea>
                </div>

                <div class="form-group">
                    <label>Enrollment Email</label>
                    <textarea type="text" class="form-control" name="enrollment_email">{{ university.getEnrollmentEmail }}</textarea>
                </div>
                <div class="form-group">
                    <label>Accountant Email</label>
                    <textarea type="text" class="form-control" name="accountant_email">{{ university.getAccountantEmail }}</textarea>
                </div>
                <div class="form-group">
                    <label>Management Email</label>
                    <textarea type="text" class="form-control" name="management_email">{{ university.getManagementEmail }}</textarea>
                </div>
            </div>
        </div><!-- .box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">Save changes</button>
        </div><!-- .box-footer -->
    </form>
</div>
{% endblock %}
{% block custom_js %}
    {% include 'partials/rte.tpl' %}
{% endblock custom_js %}
