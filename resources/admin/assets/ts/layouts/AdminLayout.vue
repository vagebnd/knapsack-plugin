<template>
  <div class="layout admin mt-5 mr-5">
    <header
      class="flex items-center justify-between bg-gray-800 p-4 text-gray-0 rounded-tl-lg rounded-tr-lg ring-1 ring-gray-800"
    >
      <div class="title flex text-gray-0">
        <slot name="title" />
      </div>
      <div class="actions">
        <slot name="actions" />
      </div>
    </header>
    <div class="body bg-gray-200 ring-1 ring-gray-300 p-4 rounded-bl-lg rounded-br-lg">
      <slot name="body" />
    </div>
  </div>
</template>
