export type ErrorPayload = { message?: string }
export type HttpError = { data?: ErrorPayload; message?: string }

export function toMessage(err: unknown): string {
  if (typeof err === 'string') return err
  if (err && typeof err === 'object') {
    const e = err as HttpError

    if (e.data?.message) return e.data.message
    if ('message' in e && typeof e.message === 'string') return e.message
  }
  return 'Something went wrong'
}
