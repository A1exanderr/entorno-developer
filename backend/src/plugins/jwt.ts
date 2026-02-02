import fp from 'fastify-plugin'
import jwt from '@fastify/jwt'

export default fp(async (fastify) => {
  fastify.register(jwt, {
    secret: process.env.JWT_SECRET || 'jwtsecret',
    cookie: {
      cookieName: 'token',
      signed: false,
    },
  })
})

/* import fp from 'fastify-plugin'
import fastifyJwt from '@fastify/jwt'
import 'dotenv/config'

export default fp(async (fastify) => {
  fastify.register(fastifyJwt, {
    secret: process.env.JWT_SECRET || 'supersecret',
    cookie: {
      cookieName: 'refresh_token',
      signed: false,
    },
    sign: {
      expiresIn: '15m', // tiempo de vida del access token
    },
  })
}) */
