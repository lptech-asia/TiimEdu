<form role="form" method="post" action="{{ MODULE_URL }}documentCreate">
    <input type="hidden" name="id" value="{{ documentsType.getId }}">
    <div class="form-group">
        <label>Tên Loại Tài Liệu</label>
        <input type="text" class="form-control" name="name" value="{{ documentsType.getName }}" required>
    </div>
    <div class="form-group">
        <label>Giới hạn tải lên</label>
        <input type="number" class="form-control" name="limit" value="{{ documentsType.getLimit }}" required>
    </div>
    <div class="form-group">
        <label>Mô tả</label>
        <textarea rows="3" type="text" class="form-control" name="description">{{ documentsType.getDescription }}</textarea>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary pull-right">Cập nhật thông tin</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
    </div>
</form>