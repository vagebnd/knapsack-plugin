export function empty(obj: unknown) {
  return obj === null || obj === undefined || obj === '' || (Array.isArray(obj) && obj.length === 0)
}
