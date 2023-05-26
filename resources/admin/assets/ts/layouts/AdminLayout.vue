<template>
  <div class="layout admin">
    <header>
      <div class="title">
        <slot name="title" />
      </div>
      <div class="actions">
        <slot name="actions" />
      </div>
    </header>
    <div class="body">
      <slot name="body" />
    </div>
  </div>
</template>
