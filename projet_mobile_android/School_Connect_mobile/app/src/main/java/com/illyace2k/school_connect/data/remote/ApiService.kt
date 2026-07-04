package com.illyace2k.school_connect.data.remote

import com.illyace2k.school_connect.data.model.*
import retrofit2.Response
import retrofit2.http.*

interface ApiService {

    @POST("login")
    suspend fun login(@Body request: LoginRequest): Response<LoginResponse>

    @POST("logout")
    suspend fun logout(): Response<Unit>

    @PUT("password")
    suspend fun updatePassword(@Body body: Map<String, String>): Response<Unit>

    @GET("eleves")
    suspend fun getEleves(): Response<ElevesResponse>

    @GET("eleves/{id}")
    suspend fun getEleve(@Path("id") id: Int): Response<EleveDetailResponse>

    @GET("eleves/{id}/notes")
    suspend fun getNotes(@Path("id") id: Int): Response<NotesResponse>

    @GET("eleves/{id}/paiements")
    suspend fun getPaiements(@Path("id") id: Int): Response<PaiementsResponse>

    @GET("eleves/{id}/absences")
    suspend fun getAbsences(@Path("id") id: Int): Response<AbsencesResponse>

    @GET("notifications")
    suspend fun getNotifications(): Response<NotificationsResponse>

    @PUT("notifications/{id}/lire")
    suspend fun marquerNotificationLue(@Path("id") id: String): Response<Unit>
}