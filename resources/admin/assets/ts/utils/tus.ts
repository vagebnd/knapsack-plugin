import * as tus from 'tus-js-client'

export default new (class Tus {
  public upload(file: File | Blob, filename: string = '') {
    console.log(file)
    return new Promise<tus.Upload>((resolve, reject) => {
      const upload: tus.Upload = new tus.Upload(file, {
        endpoint: `${import.meta.env.VITE_THEME_MANAGER_APP_URL}/upload`,
        metadata: {
          filename: file.name || filename,
        },
        chunkSize: 1.5 * 1024 * 1024,
        retryDelays: [0, 1000, 3000, 8000],
        onSuccess: () => resolve(upload),
        onError: reject,
      })

      upload.start()
    })
  }
})()
