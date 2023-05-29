<template>
  <div class="layout admin mt-5 mr-5">
    <header class="flex h-16 items-center justify-between bg-gray-800 px-5 text-white rounded-tl-md rounded-tr-md">
      <div class="title flex text-white">
        <slot name="title" />
      </div>
      <div class="actions">
        <slot name="actions" />
      </div>
    </header>
    <div class="body bg-white p-5 rounded-bl-md rounded-br-md">
      <slot name="body" />
    </div>
  </div>
</template>
