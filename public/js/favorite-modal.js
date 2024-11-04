// お気に入りの解除用フォーム
const removeFavoriteForm = document.forms.removeFavoriteForm;

// 解除の確認メッセージ
const removeMessage = document.getElementById('removeFavoriteModalLabel');

// お気に入りの解除用モーダルを開くときの処理
document.getElementById('removeFavoriteModal').addEventListener('show.bs.modal', (event) => {
    let removeButton = event.relatedTarget;
    let restaurantId = removeButton.dataset.restaurantId;
    let restaurantName = removeButton.dataset.restaurantName;

    removeFavoriteForm.action = `favorites/${restaurantId}`;
    removeMessage.textContent = `「${restaurantName}」のお気に入りを解除してもよろしいですか？`
});