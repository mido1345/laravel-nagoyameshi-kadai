// カテゴリの編集用フォーム
const editCategoryForm = document.forms.editCategoryForm;

// カテゴリの削除用フォーム
const deleteCategoryForm = document.forms.deleteCategoryForm;

// 削除の確認メッセージ
const deleteMessage = document.getElementById('deleteCategoryModalLabel');

// タグの編集用モーダルを開くときの処理
document.getElementById('editCategoryModal').addEventListener('show.bs.modal', (event) => {
  let editButton = event.relatedTarget;
  let categoryId = editButton.dataset.categoryId;
  let categoryName = editButton.dataset.categoryName;

  editCategoryForm.action = `categories/${categoryId}`;
  editCategoryForm.name.value = categoryName;
});

// タグの削除用モーダルを開くときの処理
document.getElementById('deleteCategoryModal').addEventListener('show.bs.modal', (event) => {
  let deleteButton = event.relatedTarget;
  let categoryId = deleteButton.dataset.categoryId;
  let categoryName = deleteButton.dataset.categoryName;

  deleteCategoryForm.action = `categories/${categoryId}`;
  deleteMessage.textContent = `「${categoryName}」を削除してもよろしいですか？`
});