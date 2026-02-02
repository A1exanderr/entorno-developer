import { FastifyInstance } from 'fastify'
import { login } from '../controllers/auth.controller'

export default async function (app: FastifyInstance) {
  app.post('/login', login)

  app.post('/logout', async (_, reply) => {
    reply
      .clearCookie('token', { path: '/' })
      .send({ message: 'Logout exitoso' })
  })
}

/* import { FastifyInstance } from 'fastify';
import { login, refreshToken, logout } from '../controllers/auth.controller';

export default async function authRoutes(app: FastifyInstance) {
  app.post('/login', login);
  app.post('/refresh', refreshToken);
  app.post('/logout', logout);
}
 */