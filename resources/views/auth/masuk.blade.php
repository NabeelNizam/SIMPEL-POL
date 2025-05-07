<div>
    <form action="{{ route('post_masuk') }}" method="POST">
        @csrf
        <div>
            <label for="username">
                Masukkan Username
            </label>
            <input type="text" name="username" placeholder="hellcat"/>
        </div>
        <div>
            <label for="password">
                Masukkan Password
            </label>
            <input type="password" name="password"/>
        </div>
        <button>Masuk</button>
    </form>
</div>
