@csrf
@if(isset($faq))
    @method('PUT')
@endif
<div class="form-group">
    <label>Question <span class="text-danger">*</span></label>
    <input type="text" name="question" class="form-control" value="{{ old('question', $faq->question ?? '') }}" required maxlength="255">
</div>
<div class="form-group">
    <x-admin.form-editor
        id="faq_answer"
        name="answer"
        label="Answer"
        :value="old('answer', $faq->answer ?? '')"
        required
    />
</div>
<div class="form-group">
    <label>Status</label>
    <select name="status" class="form-control" required>
        <option value="active" @selected(old('status', $faq->status ?? 'active') === 'active')>Active</option>
        <option value="inactive" @selected(old('status', $faq->status ?? 'active') === 'inactive')>Inactive</option>
    </select>
</div>
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save FAQ</button>
<a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Cancel</a>
