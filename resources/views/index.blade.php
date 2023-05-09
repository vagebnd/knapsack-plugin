@extends('layouts.public')

@section('content')
    <div class="tailwind">
        <main class="m-10 bg-white p-6 shadow-sm rounded-sm">
            <header>
                <h1 class="text-xl">{{ __('Plugin main view') }}</h1>
            </header>
            <form class="my-4" method="post">
                <input
                    type="text"
                    name="name"
                    placeholder="name"
                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                <input
                    type="submit"
                    value="Submit"
                    class="mt-2 rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
            </form>
        </div>
    </div>
@endsection
