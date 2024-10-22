<div class="modal fade" id="agentModal" tabindex="-1" aria-labelledby="agentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="agentModalLabel">Assigned Staff(s)</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('assignStaffToForm') }}" method="POST">@csrf
                <div class="modal-body">



                </div>

            </form>
        </div>
    </div>
</div>