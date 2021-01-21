let checkboxes = document.querySelectorAll('.checkbox');
checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', () => {
        checkbox.parentNode.submit();
    });
});

let deleteItems = document.querySelectorAll('.delete');
deleteItems.forEach(deleteItem => {
    deleteItem.addEventListener('click', () => {
        if (!confirm('削除してよろしいでしょうか?')) {
            return;
          }
        deleteItem.parentNode.submit();
    });
});
