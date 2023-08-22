export default class Screenshot {
  public getBlob(element: HTMLElement): Promise<Blob> {
    return new Promise(async (resolve, reject) => {
      try {
        const html2canvas = await import('html2canvas')
        const canvas = await html2canvas.default(element)
        canvas.toBlob(
          (blob) => {
            if (blob) {
              resolve(blob)
            } else {
              reject('Failed to create blob')
            }
          },
          'image/png',
          1,
        )
      } catch (error) {
        reject(error)
      }
    })
  }
}
