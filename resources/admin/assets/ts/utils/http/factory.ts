import axios from 'axios'

export class HttpError extends Error {
  url = ''
  payload = ''
  status = 0

  constructor(message: string) {
    super(message)
    this.name = 'HttpError'
  }
}

export const createClient = (baseURL: string, headers: Record<string, string> = {}) => {
  const http = axios.create({
    baseURL,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      ...headers,
    },
    timeout: 5000,
  })

  http.interceptors.response.use(
    (response) => {
      return response
    },
    (error) => {
      const response: any = error.response
      let customMessage = ''

      if (response && response.status) {
        const data: any = response.data

        switch (response.status) {
          case 401:
          case 403:
            //   flits.error('Unauthorized')
            setTimeout(() => {
              window.location.reload()
            }, 1000)
            break
          case 422:
            if (Array.isArray(data.errors)) {
              data.errors.forEach((messages: string[]) => {
                messages.forEach((message: string) => {
                  // flits.error(message)
                  customMessage += `${message}\n`
                })
              })
            } else if (data.errors && Object.keys(data.errors).length > 0) {
              Object.keys(data.errors).forEach((key: string) => {
                data.errors[key].forEach((message: string) => {
                  // flits.error(message)
                  customMessage += `${message}\n`
                })
              })
            } else if (data.message && data.message.length > 0) {
              // flits.error(data.message)
              customMessage += `${data.message}\n`
            }

            break
        }
      }

      if (axios.isCancel(error)) {
        return Promise.reject(error)
      }

      const errorMessage = error.message + '\n' + customMessage

      const httpError = new HttpError(errorMessage.trim())
      httpError.url = error.config.url
      httpError.payload = error.config.data
      httpError.stack = error.stack
      httpError.status = response && response.status ? response.status : 0

      return Promise.reject(httpError)
    },
  )

  return http
}
