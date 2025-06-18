<script src="{{ asset('assets/js/ckeditor.js') }}"></script>
<script src="{{ asset('assets/js/ckeditor-customization.js') }}"></script>
<script>
    document.addEventListener("livewire:load", () => {
        let editorInstance = null;

        function initCKEditor() {
            const textarea = document.querySelector('#ckeditor');

            if (textarea && !editorInstance) {
                ClassicEditor.create(textarea)
                    .then(editor => {
                        editorInstance = editor;
                        console.log("CKEditor initialized");
                    })
                    .catch(error => {
                        console.error("CKEditor init error", error);
                    });
            }
        }

        initCKEditor();

        Livewire.hook('message.processed', () => {
            // Reinitialize if needed
            if (!editorInstance || !document.querySelector('#ckeditor')) {
                editorInstance = null;
                initCKEditor();
            }
        });
    });
</script>
