<div class="card flex flex-col mt-3">
    <h3 class="font-normal text-xl py-4 -ml-5 border-l-4 border-blue-200 pl-4 mb-3">
        Invite a user
    </h3>

    <form method="POST" action="{{ $project->path() . '/invitations' }}">
        @csrf
        <div class="mb-3">
            <input type="email" name="email"
                class="border border-grey-500 rounded w-full py-1 px-3"
                placeholder="Email address">
        </div>
        <button type="submit" class="button">Invite</button>
    </form>
    @include('errors', ['bag' => 'invitations'])
</div>
