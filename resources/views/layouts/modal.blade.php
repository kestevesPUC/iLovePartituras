
<div class="modal fade" id="{{ $id }}" tabindex="-1" data-bs-focus="false" aria-hidden="true">
    <div class="modal-dialog {{ $size }}">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_new_address_header">
                <h2>{{ $title }}</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" id="btn-mail"data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body">
                @yield($modalBody)
            </div>
            <div class="modal-footer">
                @if(!isset($showButton) || $showButton)
                    <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">Fechar</button>
                @endif
                @yield($modalButton)
            </div>
        </div>
    </div>
</div>
